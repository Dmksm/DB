<?php
declare(strict_types=1);
namespace App\Controller;

use App\Api\User\ApiInterface as UserApi;
use App\Api\Product\ApiProductCategoryInterface as ProductCategoryApi;
use App\Api\Product\ApiProductInterface as ProductApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    private UserApi $userApi;
    private ProductCategoryApi $productCategoryApi;
    private ProductApi $productApi;
    public function __construct(
        UserApi $userApi, 
        ProductCategoryApi $productCategoryApi,
        ProductApi $productApi,
        )
    {
        $this->userApi = $userApi;
        $this->productCategoryApi = $productCategoryApi;
        $this->productApi = $productApi;
    }

    #[Route('/login')]
    #[Route('/')]
    public function loginPage(): Response
    {
        // TODO: удалить получение пользователя и поправить метод loginPage
        $user = $this->userApi->getUserInfo(1);
        $name = ($user) ? $user->getFirstName() : 'anonymous';
        return $this->render('authorization/login.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/general')]
    public function generalPage(): Response
    {
        $user = $this->userApi->getUserInfo(1);
        $name = ($user) ? $user->getFirstName() : 'anonymous';
        return $this->render('general/general.html.twig', [
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
        return $this->render('product/product.html.twig', [
            'name' => $name,
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
        return $this->render('product/product.html.twig', [
            'id' => $id,
            'name' => $name,
            'descryption' => $descryption, 
            'category' => $category, 
            'cost' => $cost, 
            'photo' => $photo
        ]);
    }

    #[Route('/add_product')]
    public function addProduct(Request $request): Response
    {
        $this->productApi->addProduct(
            'банан',
            'Это банан',
            1,
            100,
            'path'
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }
}