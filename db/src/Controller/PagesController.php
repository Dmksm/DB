<?php
declare(strict_types=1);
namespace App\Controller;

use App\Api\User\ApiInterface as UserApi;
use App\Api\Product\ApiProductInterface as ProductApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    private UserApi $userApi;
    private ProductApi $productApi;
    public function __construct(/*UserApi $userApi,*/ ProductApi $productApi)
    {
        //$this->userApi = $userApi;
        $this->productApi = $productApi;
    }

    #[Route('/')]
    public function loginPage(): Response
    {
        // удалить получение пользователя и поправить метод loginPage
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
    public function GetProductCategory(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
        $user = $this->productApi->getProductCategory(1);
        $name = ($user) ? $user->getname() : 'anonymous';
        return $this->render('authorization/login.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/add_product_category')]
    public function AddProductCategory(Request $request): Response
    {
        $this->productApi->AddProductCategory(
            'Продукты'
        );

        $response = new Response(
            'Ok',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );

        return $response;
    }
}