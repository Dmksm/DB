<?php
declare(strict_types=1);
namespace App\Controller;

use App\Api\User\ApiInterface as UserApi;
use App\Api\Product\ApiProductCategoryInterface as ProductCategoryApi;
use App\Api\Product\ApiProductInterface as ProductApi;
use App\Api\Storage\ApiStorageInterface as StorageApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    private UserApi $userApi;
    private ProductCategoryApi $productCategoryApi;
    private ProductApi $productApi;
    private StorageApi $storageApi;
    public function __construct(
        UserApi $userApi, 
        ProductCategoryApi $productCategoryApi,
        ProductApi $productApi,
        StorageApi $storageApi,
        )
    {
        $this->userApi = $userApi;
        $this->productCategoryApi = $productCategoryApi;
        $this->productApi = $productApi;
        $this->storageApi = $storageApi;
    }

    #[Route('/login')]
    public function loginPage(): Response
    {
        // TODO: удалить получение пользователя и поправить метод loginPage
        $user = $this->userApi->getUserInfo(1);
        $name = ($user) ? $user->getFirstName() : 'anonymous';
        return $this->render('authorization/login.html.twig', [
            'name' => $name,
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
}