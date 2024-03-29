<?php
declare(strict_types=1);
namespace App\Controller;

use App\Api\StaffInfo\ApiStaffInfoInterface as StaffInfoApi;
use App\Api\Client\ApiClientInterface as ClientApi;
use App\Api\Order\ApiOrderInterface as OrderApi;
use App\Api\Product\ApiProductCategoryInterface as ProductCategoryApi;
use App\Api\Product\ApiProductInterface as ProductApi;
use App\Api\ProductPurchase\ApiProductPurchaseInterface as ProductPurchaseApi;
use App\Api\ProductInStorage\ApiProductInStorageInterface as ProductInStorageApi;
use App\Common\CategoryType;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PagesController extends AbstractController
{
    private const IMAGES_FOLDER = "images/";
    private const DEFAULT_IMAGE = "unknown_person.jpg";
    private const DATE_TIME_FORMAT = 'Y-m-d';

    private StaffInfoApi $staffInfoApi;
    private ClientApi $clientApi;
    private OrderApi $orderApi;
    private ProductCategoryApi $productCategoryApi;
    private ProductApi $productApi;
    private ProductPurchaseApi $productPurchaseApi;
    private ProductInStorageApi $productInStorageApi;
    public function __construct(
        StaffInfoApi $staffInfoApi,
        ProductCategoryApi $productCategoryApi,
        ProductApi $productApi,
        ProductPurchaseApi $productPurchaseApi,
        ProductInStorageApi $productInStorageApi,
        ClientApi $clientApi,
        OrderApi $orderApi,
        private readonly LoggerInterface $logger
        )
    {
        $this->staffInfoApi = $staffInfoApi;
        $this->clientApi = $clientApi;
        $this->orderApi = $orderApi;
        $this->productCategoryApi = $productCategoryApi;
        $this->productApi = $productApi;
        $this->productPurchaseApi = $productPurchaseApi;
        $this->productInStorageApi = $productInStorageApi;
    }

    #[Route('/errorPage/{statusCode}', 'errorPage')]
    public function errorPage(Request $request): Response
    {
        $statusCode = $request->attributes->get('statusCode');
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->render('error/error.html.twig', [
            'loginPage' => $loginPage,
            'statusCode' => $statusCode,
        ]);
    }

    #[Route('/loginPage', 'loginPage')]
    #[Route('/')]
    public function loginPage(Request $request): Response
    {
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPage = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $auth = $this->generateUrl('authorization',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $errorPageUrl = $this->generateUrl('errorPage', ['statusCode' => 401], UrlGeneratorInterface::ABSOLUTE_URL);
        $registerPageUrl = $this->generateUrl('registerPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->render('authorization/login.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'basketPage' => $basketPage,
            'authorizationUrl' => $auth,
            'errorPageUrl' => $errorPageUrl,
            'registerPage' => $registerPageUrl
        ]);
    }

    #[Route('/auth', 'authorization')]
    public function auth(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON');
        }
        $email = $data['email'];
        $password = $data['password'];

        $client = $this->clientApi->getClientByEmailAndPassword($email, $password);

        if ($client)
        {
            $expire = time() + 36000;
            $cookie = new Cookie('id', strval($client->getId()), $expire);
            $response = new Response();
            $response->headers->setCookie($cookie);
            $cookie = new Cookie('admin', strval(0), $expire);
            $response->headers->setCookie($cookie);
            return $response;
        }

        $admin = $this->staffInfoApi->getStaffInfoByEmailAndPassword($email, $password);

        if ($admin)
        {
            $expire = time() + 36000;
            $cookie = new Cookie('id', strval($admin->getId()), $expire);
            $response = new Response();
            $response->headers->setCookie($cookie);
            $cookie = new Cookie('admin', strval(1), $expire);
            $response->headers->setCookie($cookie);
            return $response;
        }

        $response = new Response();
        $response->setStatusCode(401, 'неверная почта или пароль');
        return $response;
    }

    #[Route('/mainPage', 'mainPage')]
    public function mainPage(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = $request->cookies->get('admin');
        if (!$request->cookies->has('admin'))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = (bool)($isAdmin);
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPage = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $text = $request->query->get('searchText');
        $this->logger->alert("text $text " . $request->getContent());
        $products = (empty($text)) ?  $this->productApi->getAllProducts() :
            $this->productApi->getProductsByIncludingString($text);
        $productsView = [];
        foreach ($products as $product)
        {
            $productsView[] = [
                'name' => $product->getName(),
                'image' => self::IMAGES_FOLDER . $product->getPhoto(),
                'cost' => $product->getCost(),
                'link' => $this->generateUrl('productPage',['id' => $product->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
            ];
        }
        return $this->render('general/general.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'basketPage' => $basketPage,
            'products' => $productsView,
            'isAdmin' => $isAdmin,
        ]);
    }

    #[Route('/submitOrder', 'submitOrder')]
    public function submitOrder(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = $request->cookies->get('admin');
        if (!$request->cookies->has('admin'))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = (bool)($isAdmin);

        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON');
        }
        try {
            $products = $data['products'];
            $sum = $data['cost'];
            $idOrder = $this->orderApi->addOrder($id,$sum,new \DateTimeImmutable(),0, 'you-la');
            foreach($products as $productId => $count)
            {
                $this->productPurchaseApi->addProductPurchase($productId, $idOrder, 1, new \DateTimeImmutable, (new \DateTimeImmutable), 0);
                $this->productInStorageApi->updateProductInStorage(
                    $this->productInStorageApi->getProductInStorageByProductAndStorage($productId, 1)->getId(),
                    $productId,
                    1,
                    $this->productInStorageApi->getProductInStorageByProductAndStorage($productId, 1)->getCount() - (int)$count
                );
            }
        }
        catch (\Throwable $e)
        {
            $this->logger->alert("err $e");
            return new Response('', 400);
        }

        return new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }

    #[Route('/reportPage', 'reportPage')]
    public function reportPage(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id)) {
            $this->redirectToErrorPage(401);
        }
        $isAdmin = $request->cookies->get('admin');
        if (!$request->cookies->has('admin'))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = (bool)($isAdmin);
        if (!$isAdmin)
        {
            return $this->redirectToErrorPage(404);
        }
        $errorPageUrl = $this->generateUrl('errorPage', ['statusCode' => 401], UrlGeneratorInterface::ABSOLUTE_URL);
        $updateOrderUrl = $this->generateUrl('updateOrder', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $orders = $this->orderApi->getAllOrders();
        $reports = [];
        foreach ($orders as $order)
        {
            $client = $this->clientApi->getClient($order->getIdClient());
            if (empty($client))
            {
                $client = $this->staffInfoApi->getStaffInfo($order->getIdClient());
            }
            $reports[] = [
                'id' => $order->getId(),
                'clientId' => $order->getIdClient(),
                'orderDate' => ($order->getOrderDate())->format(self::DATE_TIME_FORMAT),
                'totalCost' => $order->getSum(),
                'status' => $order->getStatus(),
                'address' => $order->getAddress(),
                'clientName' => $client->getFirstName()
            ];
        }
        return $this->render('report/report_page.html.twig', [
            'updateOrderUrl' => $updateOrderUrl,
            'errorPageUrl' => $errorPageUrl,
            'isAdmin' => $isAdmin,
            'reports' => $reports,
        ]);
    }

    #[Route('/updateOrder', 'updateOrder')]
    public function updateOrder(Request $request): Response
    {
        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = $request->cookies->get('admin');
        if (!$request->cookies->has('admin'))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = (bool)($isAdmin);
        if (!$isAdmin)
        {
            throw new \InvalidArgumentException('User can not change status');
        }
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON');
        }
        $statusOrder = $data['status'];
        $orderId = $data['id'];
        $order = $this->orderApi->getOrder((int)$orderId);
        $this->orderApi->updateOrder(
            $order->getId(),
            $order->getIdClient(),
            $order->getSum(),
            $order->getOrderDate(),
            (int)$statusOrder,
            $order->getAddress(),
        );
        return $response;
    }

    #[Route('/successOrderPage', 'successOrderPage')]
    public function successOrderPage(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id)) {
            $this->redirectToErrorPage(401);
        }
        $isAdmin = $request->cookies->get('admin');
        if (!$request->cookies->has('admin'))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = (bool)($isAdmin);
        $mainPageUrl = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->render('basket/success_order.html.twig', [
            'mainPage' => $mainPageUrl,
            'isAdmin' => $isAdmin
        ]);
    }

    #[Route('/profilePage', 'profilePage')]
    public function profilePage(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id)) {
            $this->redirectToErrorPage(401);
        }
        $isAdmin = $request->cookies->get('admin');
        if (!$request->cookies->has('admin'))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = (bool)($isAdmin);
        $user = ($isAdmin) ? $this->staffInfoApi->getStaffInfo($id) : $this->clientApi->getClient($id);
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPage = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $updateUser = $this->generateUrl('updateUser',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $errorPageUrl = $this->generateUrl('errorPage', ['statusCode' => 401], UrlGeneratorInterface::ABSOLUTE_URL);

        $userView = [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'birthday' => $user->getBirthday()->format(self::DATE_TIME_FORMAT),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'patronymic' => $user->getPatronymic(),
            'imagePath' => self::IMAGES_FOLDER . $user->getPhoto(),
            'telephone' => $user->getTelephone(),
            'position' => ($isAdmin) ? $user->getPosition() : "",
        ];

        return $this->render('profile/profile.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'basketPage' => $basketPage,
            'userInfo' => $userView,
            'updateUserUrl' => $updateUser,
            'errorPageUrl' => $errorPageUrl,
            'isAdmin' => $isAdmin
        ]);
    }

    #[Route('/registerPage', 'registerPage')]
    public function registerPage(Request $request): Response
    {
        $registerPage = $this->generateUrl('registerPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $registerUrl = $this->generateUrl('register',[], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->render('register/register.html.twig', [
            'registerPage' => $registerPage,
            'loginPage' => $loginPage,
            'basketPage' => $basketPage,
            'registerUrl' => $registerUrl,
        ]);
    }

    #[Route('/register', 'register')]
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON');
        }

        $client = $this->clientApi->getClientByEmailAndPassword($data['email'],$data['password']);
        $staff = $this->staffInfoApi->getStaffInfoByEmailAndPassword($data['email'],$data['password']);

        if(!$client && !$staff)
        {
            $this->clientApi->addClient(
                $data['first_name'],
                $data['last_name'],
                \DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $data['birthday']),
                $data['email'],
                $data['password'],
                $data['patronymic'],
                $data['photo'] ?? self::DEFAULT_IMAGE,
                $data['telephone']
            );
            $status = Response::HTTP_OK;
        }
        else
        {
            $status = Response::HTTP_BAD_REQUEST;
        }


        return new Response(
            'Ok',
            $status,
            ['content-type' => 'text/html']
        );
    }

    #[Route('/basketPage', 'basketPage')]
    public function basketPage(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id)) {
            $this->redirectToErrorPage(401);
        }
        $isAdmin = $request->cookies->get('admin');
        if (!$request->cookies->has('admin'))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = (bool)($isAdmin);
        if ($isAdmin)
        {
            return $this->redirectToErrorPage(404);
        }
        $loginPageUrl = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPageUrl = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPageUrl = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $submitOrderUrl = $this->generateUrl('submitOrder',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $successOrderPageUrl = $this->generateUrl('successOrderPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $errorPageUrl = $this->generateUrl('errorPage', ['statusCode' => 401], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->render('basket/basket.html.twig', [
            'loginPage' => $loginPageUrl,
            'mainPage' => $mainPageUrl,
            'basketPage' => $basketPageUrl,
            'submitOrderUrl' => $submitOrderUrl,
            'successOrderPageUrl' => $successOrderPageUrl,
            'errorPageUrl' => $errorPageUrl,
            'isAdmin' => $isAdmin,
        ]);
    }

    #[Route('/product/{id}', 'productPage')]
    public function productPage(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id)) {
            $this->redirectToErrorPage(401);
        }
        $isAdmin = $request->cookies->get('admin');
        if (!$request->cookies->has('admin'))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = (bool)($isAdmin);
        $productId = $request->attributes->get('id');
        $product = $this->productApi->getProduct((int)$productId);
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPage = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $updateProductUrl = $this->generateUrl('updateProduct',[], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->render('product/product.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'basketPage' => $basketPage,
            'description' => $product->getdescription(),
            'productId' => $product->getId(),
            'name' => $product->getName(),
            'cost' => $product->getCost(),
            'category' => $this->productCategoryApi->getProductCategory($product->getCategory())->getName(),
            'imagePath' => self::IMAGES_FOLDER . $product->getPhoto(),
            'isAdmin' => $isAdmin,
            'updateProductUrl' => $updateProductUrl
        ]);
    }

    #[Route('/updateProduct', 'updateProduct')]
    public function updateProduct(Request $request): Response
    {
        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = $request->cookies->get('admin');
        if (!$request->cookies->has('admin'))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = (bool)($isAdmin);
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON');
        }
        if (!$isAdmin)
        {
            return $this->redirectToErrorPage(403);
        }
        $this->logger->alert("category " . CategoryType::fromCategoryTypeName($data['category'])->value);
        $this->productApi->updateProduct(
            (int)$data['id'],
            $data['name'],
            $data['description'],
            CategoryType::fromCategoryTypeName($data['category'])->value,
            (int)$data['cost'],
            str_replace(self::IMAGES_FOLDER, "", $data['photo']),
        );

        return $response;
    }

    #[Route('/updateUser', 'updateUser')]
    public function updateUser(Request $request): Response
    {
        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = $request->cookies->get('admin');
        if (!$request->cookies->has('admin'))
        {
            return $this->redirectToErrorPage(401);
        }
        $isAdmin = (bool)($isAdmin);
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON');
        }

        if ($isAdmin)
        {
            $this->staffInfoApi->updateStaffInfo(
                $id,
                $data['firstName'],
                $data['lastName'],
                \DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $data['birthday']),
                $data['email'],
                $data['password'],
                $data['patronymic'] ?? null,
                $data['photo'] ?? self::DEFAULT_IMAGE,
                $data['telephone'] ?? null,
                $data['position'] ?? null,
            );
        }
        else
        {
            $this->clientApi->updateClient(
                $id,
                $data['firstName'],
                $data['lastName'],
                \DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $data['birthday']),
                $data['email'],
                $data['password'],
                $data['patronymic'] ?? null,
                $data['photo'] ?? self::DEFAULT_IMAGE,
                $data['telephone'] ?? null,
            );
        }

        return $response;
    }

    private function isUserExist(int $id): bool
    {
        return !empty($this->clientApi->getClient(($id)) || !empty($this->staffInfoApi->getStaffInfo($id)));
    }

    private function redirectToErrorPage(int $statusCode): Response
    {
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->render('error/error.html.twig', [
            'loginPage' => $loginPage,
            'statusCode' => $statusCode,
        ]);
    }
}