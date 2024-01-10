<?php
declare(strict_types=1);
namespace App\Controller;

use App\Api\User\ApiInterface as UserApi;
use App\Api\Client\ApiClientInterface as ClientApi;
use App\Api\Order\ApiOrderInterface as OrderApi;
use App\Api\Product\ApiProductCategoryInterface as ProductCategoryApi;
use App\Api\Product\ApiProductInterface as ProductApi;
use App\Api\Storage\ApiStorageInterface as StorageApi;
use App\Api\ProductInStorage\ApiProductInStorageInterface as ProductInStorageApi;
use App\Api\StaffInStorage\ApiStaffInStorageInterface as StaffInStorageApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PagesController extends AbstractController
{
    private UserApi $userApi;
    private ClientApi $clientApi;
    private OrderApi $orderApi;
    private ProductCategoryApi $productCategoryApi;
    private ProductApi $productApi;
    private StorageApi $storageApi;
    private ProductInStorageApi $productInStorageApi;
    private StaffInStorageApi $staffInStorageApi;
    public function __construct(
        UserApi $userApi, 
        ProductCategoryApi $productCategoryApi,
        ProductApi $productApi,
        StorageApi $storageApi,
        ProductInStorageApi $productInStorageApi,
        StaffInStorageApi $staffInStorageApi,
        ClientApi $clientApi,
        OrderApi $orderApi,
        )
    {
        $this->userApi = $userApi;
        $this->clientApi = $clientApi;
        $this->orderApi = $orderApi;
        $this->productCategoryApi = $productCategoryApi;
        $this->productApi = $productApi;
        $this->storageApi = $storageApi;
        $this->productInStorageApi = $productInStorageApi;
        $this->staffInStorageApi = $staffInStorageApi;
    }

    #[Route('/loginPage', 'loginPage')]
    public function loginPage(): Response
    {
        // TODO: удалить получение пользователя и поправить метод loginPage
        $user = $this->userApi->getUserInfo(1);
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPage = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $productPage = $this->generateUrl('productPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $name = ($user) ? $user->getFirstName() : 'anonymous';
        return $this->render('mockPages/loginPage.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'productPage' => $productPage,
            'basketPage' => $basketPage,
        ]);
    }

    #[Route('/mainPage', 'mainPage')]
    public function mainPage(): Response
    {
        // TODO: удалить получение пользователя и поправить метод loginPage
        $user = $this->userApi->getUserInfo(1);
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPage = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $productPage = $this->generateUrl('productPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $name = ($user) ? $user->getFirstName() : 'anonymous';
        return $this->render('mockPages/mainPage.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'productPage' => $productPage,
            'basketPage' => $basketPage,
        ]);
    }

    #[Route('/productPage', 'productPage')]
    public function productPage(): Response
    {
        // TODO: удалить получение пользователя и поправить метод loginPage
        $user = $this->userApi->getUserInfo(1);
        $products = $this->productApi->getAllProducts();
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPage = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $productPage = $this->generateUrl('productPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $name = ($user) ? $user->getFirstName() : 'anonymous';
        return $this->render('mockPages/productPage.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'productPage' => $productPage,
            'basketPage' => $basketPage,
        ]);
    }

    #[Route('/basketPage', 'basketPage')]
    public function basketPage(): Response
    {
        // TODO: удалить получение пользователя и поправить метод loginPage
        $user = $this->userApi->getUserInfo(1);
        $products = $this->productApi->getAllProducts();
        $loginPage = $this->generateUrl('loginPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $mainPage = $this->generateUrl('mainPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $productPage = $this->generateUrl('productPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $basketPage = $this->generateUrl('basketPage',[], UrlGeneratorInterface::ABSOLUTE_URL);
        $name = ($user) ? $user->getFirstName() : 'anonymous';
        return $this->render('mockPages/basketPage.html.twig', [
            'loginPage' => $loginPage,
            'mainPage' => $mainPage,
            'productPage' => $productPage,
            'basketPage' => $basketPage,
        ]);
    }

    #[Route('/register')]
    public function register(Request $request): Response
    {
        $this->userApi->registerUser(
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

    #[Route('/get_product_category')]
    public function getProductCategory(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $productCategory = $this->productCategoryApi->getProductCategory(1);
        $name = ($productCategory) ? $productCategory->getname() : 'anonymous';
        return $this->render('authorization/login.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/get_all_products')]
    public function getAllProducts(): Response
    {
        $products = $this->productApi->getAllProducts();
        return $this->render('Product/get_all_products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/get_all_products_count', name:'get_all_products_count')]
    public function getAllProductsCount(): Response
    {
        $products = $this->productApi->getAllProducts();
        return $this->render('Product/get_all_products_count.html.twig', [
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
        return $this->render('Product/login.html.twig', [
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
        return $this->render('Product/get_all_products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/get_products_by_including_string')]
    public function getProductsByIncludingString(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $products = $this->productApi->getProductsByIncludingString('ан');
        return $this->render('Product/get_all_products.html.twig', [
            'products' => $products,
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
            'name' => $name,
        ]);
    } 
    
    #[Route('/add_staff_in_storage')]
    public function addStaffInStorage(Request $request): Response
    {
        $this->staffInStorageApi->addStaffInStorage(
            $this->userApi->getUserInfo(1)->getId(),
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
        $name = ($staffInStorage) ? $this->userApi->getUserInfo(1)->getFirstName() : 'anonymous';
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

    #[Route('/get_order')]
    public function getOrder(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $order = $this->orderApi->getOrder(1);
        return $this->render('authorization/login.html.twig', [
            'name' => $order->getAddress(),
        ]);
    }
}