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
        //добавить метод проверки на пользователя, метод вернет id который зашьется в куки
        $expire = time() + 36000;
        $cookie = new Cookie('id', '1', $expire);
        $response = new Response();
        $response->headers->setCookie($cookie);
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
        return new Response();
    }

    #[Route('/successOrderPage', 'successOrderPage')]
    public function successOrderPage(): Response
    {
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

    #[Route('/basketPage', 'basketPage')]
    public function basketPage(): Response
    {
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

    #[Route('/update_staff_info')]
    public function updateStaffInfo(Request $request): Response
    {
        $this->staffInfoApi->updateStaffInfo(
            1,
            'Алена',
            'Золотцева',
            (new \DateTimeImmutable()),
            'alena123@mail.com',
            '123456Alena',
            'Vecheslavovna',
            '/newpath',
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


    #[Route('/get_product_category')]
    public function getProductCategory(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $productCategory = $this->productCategoryApi->getProductCategory(1);
        $name = ($productCategory) ? $productCategory->getname() : 'anonymous';
        return $this->render('product/product.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/get_all_products')]
    public function getAllProducts(): Response
    {
        $products = $this->productApi->getAllProducts();
        return $this->render('product/get_all_products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/get_all_products_count', 'get_all_products_count')]
    public function getAllProductsCount(): Response
    {
        $products = $this->productApi->getAllProducts();
        return $this->render('product/get_all_products_count.html.twig', [
            'productsCount' => count($products),
        ]);
    }

    #[Route('/add_product_category')]
    public function addProductCategory(Request $request): Response
    {
        $this->productCategoryApi->addProductCategory(
            'Продукты'
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/update_product_category')]
    public function updateProductCategory(Request $request): Response
    {
        $this->productCategoryApi->updateProductCategory(
            2,
            'Овощи'
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/get_product')]
    public function getProduct(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $product = $this->productApi->getProduct(1);
        $id = $product->getId();
        $name = $product->getName();
        $descryption = $product->getDescryption();
        $category = $this->productCategoryApi->getProductCategory($product->getCategory())->getName();
        $cost = $product->getCost();
        $photo = $product->getPhoto();
        return $this->render('product/product.html.twig', [
            'id' => $id,
            'name' => $name,
            'descryption' => $descryption, 
            'category' => $category, 
            'cost' => $cost, 
            'photo' => $photo
        ]);
    }

    #[Route('/get_products_by_category')]
    public function getProductsByCategory(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $products = $this->productApi->getProductsByCategory(1);
        return $this->render('product/get_all_products.html.twig', [
            'products' => $products,
        ]);
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

    #[Route('/add_product')]
    public function addProduct(Request $request): Response
    {
        $this->productApi->addProduct(
            'яблоко',
            'Это яблоко',
            1,
            200,
            'path'
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/update_product')]
    public function updateProduct(Request $request): Response
    {
        $this->productApi->updateProduct(
            2,
            'картошка',
            'Это картошка',
            2,
            300,
            'path'
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/add_storage')]
    public function addStorage(Request $request): Response
    {
        $this->storageApi->addStorage(
            'Йошкар-ола',
            'Пушкина',
            '10'
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/get_storage')]
    public function getStorage(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $storage = $this->storageApi->getStorage(1);
        $name = ($storage) ? $storage->getCity() : 'anonymous';
        return $this->render('authorization/login.html.twig', [
            'homePageLink' => $this->generateUrl(''),
            'name' => $name,
        ]);
    }

    #[Route('/add_staff_in_storage')]
    public function addStaffInStorage(Request $request): Response
    {
        $this->staffInStorageApi->addStaffInStorage(
            $this->staffInfoApi->getStaffInfo(1)->getId(),
            $this->storageApi->getStorage(1)->getId(),
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/get_staff_in_storage')]
    public function getStaffInStorage(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $staffInStorage = $this->staffInStorageApi->getStaffInStorage(1);
        $name = ($staffInStorage) ? $this->staffInfoApi->getStaffInfo(1)->getFirstName() : 'anonymous';
        return $this->render('authorization/login.html.twig', [
            'name' => $this->generateUrl('basketPage'),
        ]);
    }

    #[Route('/add_client')]
    public function addClient(Request $request): Response
    {
        $this->clientApi->addClient(
            'Роман',
            'Смирнов',
            (new \DateTimeImmutable()),
            'roman123@mail.com',
            '123456Roman',
            'Vecheslavovich',
            '/path',
            '+71239870010',
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
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

    #[Route('/get_client')]
    public function getClient(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $client = $this->clientApi->getClient(1);
        return $this->render('authorization/login.html.twig', [
            'name' => $client->getFirstName(),
        ]);
    }

    #[Route('/add_product_in_storage')]
    public function addProductInStorage(Request $request): Response
    {
        $this->productInStorageApi->addProductInStorage(
            1,
            1,
            1
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/update_product_in_storage')]
    public function updateProductInStorage(Request $request): Response
    {
        $this->productInStorageApi->updateProductInStorage(
            1,
            1,
            1,
            10
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/get_product_in_storage')]
    public function getProductInStorage(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $productInStorageApi = $this->productInStorageApi->getProductInStorage(1);
        return $this->render('authorization/login.html.twig', [
            'name' => $this->storageApi->getStorage($productInStorageApi->getIdProduct())->getCity(),
        ]);
    }

    #[Route('/add_order')]
    public function addOrder(Request $request): Response
    {
        $this->orderApi->addOrder(
            1,
            100,
            new \DateTimeImmutable(),
            0,
            'address'
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/update_order')]
    public function updateOrder(Request $request): Response
    {
        $this->orderApi->updateOrder(
            1,
            1,
            100,
            new \DateTimeImmutable(),
            0,
            'newAddress'
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/get_order')]
    public function getOrder(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $order = $this->orderApi->getOrder(1);
        return $this->render('authorization/login.html.twig', [
            'name' => $order->getAddress(),
        ]);
    }

    #[Route('/add_product_purchase')]
    public function addProductPurchase(Request $request): Response
    {
        $this->productPurchaseApi->addProductPurchase(
            1,
            1,
            1,
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            0
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/update_product_purchase')]
    public function updateProductPurchase(Request $request): Response
    {
        $this->productPurchaseApi->updateProductPurchase(
            1,
            1,
            1,
            1,
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
            1
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    #[Route('/get_product_purchase')]
    public function getProductPurchase(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $productPurchase = $this->productPurchaseApi->getProductPurchase(1);
        return $this->render('authorization/login.html.twig', [
            'name' => $this->productApi->getProduct($productPurchase->getIdProduct())->getName(),
        ]);
    }

    private function isUserExist(int $id): bool
    {
        return !empty($this->clientApi->getClient(($id)) || !empty($this->staffInfoApi->getStaffInfo($id)));
    }
}