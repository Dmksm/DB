<?php
declare(strict_types=1);
namespace App\Controller;

use App\Api\StaffInfo\ApiStaffInfoInterface as StaffInfoApi;
use App\Api\Client\ApiClientInterface as ClientApi;
use App\Api\Order\ApiOrderInterface as OrderApi;
use App\Api\Product\ApiProductCategoryInterface as ProductCategoryApi;
use App\Api\Product\ApiProductInterface as ProductApi;
use App\Api\ProductPurchase\ApiProductPurchaseInterface as ProductPurchaseApi;
use App\Api\Storage\ApiStorageInterface as StorageApi;
use App\Api\ProductInStorage\ApiProductInStorageInterface as ProductInStorageApi;
use App\Api\StaffInStorage\ApiStaffInStorageInterface as StaffInStorageApi;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PagesController extends AbstractController
{
    private const DATE_TIME_FORMAT = 'd/m/Y';

    private StaffInfoApi $staffInfoApi;
    private ClientApi $clientApi;
    private OrderApi $orderApi;
    private ProductCategoryApi $productCategoryApi;
    private ProductApi $productApi;
    private ProductPurchaseApi $productPurchaseApi;
    private StorageApi $storageApi;
    private ProductInStorageApi $productInStorageApi;
    private StaffInStorageApi $staffInStorageApi;
    public function __construct(
        StaffInfoApi $staffInfoApi,
        ProductCategoryApi $productCategoryApi,
        ProductApi $productApi,
        ProductPurchaseApi $productPurchaseApi,
        StorageApi $storageApi,
        ProductInStorageApi $productInStorageApi,
        StaffInStorageApi $staffInStorageApi,
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
        $this->storageApi = $storageApi;
        $this->productInStorageApi = $productInStorageApi;
        $this->staffInStorageApi = $staffInStorageApi;
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
        // TODO: удалить получение пользователя и поправить метод loginPage
        $staffInfo = $this->staffInfoApi->getStaffInfo(1);
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPage = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $auth = $this->generateUrl('authorization',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $errorPageUrl = $this->generateUrl('errorPage', ['statusCode' => 401], UrlGeneratorInterface::ABSOLUTE_URL);
        $name = ($staffInfo) ? $staffInfo->getFirstName() : 'anonymous';
        return $this->render('authorization/login.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'basketPage' => $basketPage,
            'authorizationUrl' => $auth,
            'errorPageUrl' => $errorPageUrl,
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
        if (empty($id) || !$this->isUserExist($id)) {
            $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
            return $this->render('error/error.html.twig', [
                'loginPage' => $loginPage,
                'statusCode' => 401,
            ]);
        }
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
                'image' => "images/" . $product->getPhoto(),
                'cost' => $product->getCost(),
                'link' => $this->generateUrl('productPage',['id' => $product->getId()], UrlGeneratorInterface::ABSOLUTE_URL)
            ];
        }
        return $this->render('general/general.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'basketPage' => $basketPage,
            'products' => $productsView,
        ]);
    }

    #[Route('/submitOrder', 'submitOrder')]
    public function submitOrder(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id)) {
            $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
            return $this->render('error/error.html.twig', [
                'loginPage' => $loginPage,
                'statusCode' => 401,
            ]);
        }

        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON');
        }
        try {
            $products = $data['products'];
            $sum = $data['cost'];
            $this->orderApi->addOrder($id,$sum,new \DateTimeImmutable(),0, 'you-la');
            foreach($products as $productId => $count)
            {
                $this->productPurchaseApi->addProductPurchase($productId, $id, 1, new \DateTimeImmutable, (new \DateTimeImmutable), 0);
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

        return new Response();
    }

    #[Route('/successOrderPage', 'successOrderPage')]
    public function successOrderPage(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id)) {
            $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
            return $this->render('error/error.html.twig', [
                'loginPage' => $loginPage,
                'statusCode' => 401,
            ]);
        }
        $mainPageUrl = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->render('basket/success_order.html.twig', [
            'mainPage' => $mainPageUrl,
        ]);
    }

    #[Route('/profilePage', 'profilePage')]
    public function profilePage(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id)) {
            $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
            return $this->render('error/error.html.twig', [
                'loginPage' => $loginPage,
                'statusCode' => 401,
            ]);
        }
        $user = $this->clientApi->getClient($id) ?? $this->staffInfoApi->getStaffInfo($id);
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
            'imagePath' => "images/" . $user->getPhoto(),
            'telephone' => $user->getTelephone(),
            'position' => $user->getPosition()
        ];

        return $this->render('profile/profile.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'basketPage' => $basketPage,
            'userInfo' => $userView,
            'updateUserUrl' => $updateUser,
            'errorPageUrl' => $errorPageUrl,
        ]);
    }

    #[Route('/registerPage', 'registerPage')]
    public function registerPage(Request $request): Response
    {
        return $this->render('register/register.html.twig', [
        ]);
    }

    #[Route('/basketPage', 'basketPage')]
    public function basketPage(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id)) {
            $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
            return $this->render('error/error.html.twig', [
                'loginPage' => $loginPage,
                'statusCode' => 401,
            ]);
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
            'errorPageUrl' => $errorPageUrl
        ]);
    }

    #[Route('/product/{id}', 'productPage')]
    public function productPage(Request $request): Response
    {
        $id = (int)$request->cookies->get('id');
        if (empty($id) || !$this->isUserExist($id)) {
            $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
            return $this->render('error/error.html.twig', [
                'loginPage' => $loginPage,
                'statusCode' => 401,
            ]);
        }
        $id = $request->attributes->get('id');
        $product = $this->productApi->getProduct((int)$id);
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPage = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        return $this->render('product/product.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'basketPage' => $basketPage,
            'description' => $product->getDescryption(),
            'id' => $product->getId(),
            'name' => $product->getName(),
            'cost' => $product->getCost(),
            'category' => $this->productCategoryApi->getProductCategory($product->getCategory())->getName(),
            'imagePath' => "images/" . $product->getPhoto(),
        ]);
    }

    #[Route('/register', 'register')]
    public function register(Request $request): Response
    {
        $this->staffInfoApi->addStaffInfo(
            'Алена',
            'Золотцева',
            (new \DateTimeImmutable()),
            'alena123@mail.com',
            '123456Alena',
            'Vecheslavovna',
            '/path',
            '+71239870010',
            'reseller'
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/search', 'search')]
    public function getProductsByIncludingString(Request $request): Response
    {
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPage = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $search = $this->generateUrl('search',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $products = $this->productApi->getProductsByIncludingString('ан');
        return $this->render('general/general.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'basketPage' => $basketPage,
            'products' => $products,
            'search' =>  ''
        ]);
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
        if (empty($id) || !$this->isUserExist($id)) {
            $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
            return $this->render('error/error.html.twig', [
                'loginPage' => $loginPage,
                'statusCode' => 401,
            ]);
        }
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON');
        }

        try
        {
            $this->staffInfoApi->updateStaffInfo(
                $id,
                $data['firstName'],
                $data['lastName'],
                \DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $data['birthday']),
                $data['email'],
                $data['password'],
                $data['patronymic'] ?? null,
                $data['photo'] ?? null,
                $data['telephone'] ?? null,
                $data['position'] ?? null,
            );
        }
        catch (\Throwable $e)
        {
            $response->setStatusCode(400);
        }

        return $response;
    }

    private function isUserExist(int $id): bool
    {
        return !empty($this->clientApi->getClient(($id)) || !empty($this->staffInfoApi->getStaffInfo($id)));
    }
}