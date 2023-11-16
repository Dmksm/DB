<?php
declare(strict_types=1);
namespace App\Controller;

use App\Api\User\ApiInterface as UserApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    private UserApi $userApi;
    public function __construct(UserApi $userApi)
    {
        $this->userApi = $userApi;
    }

    #[Route('/')]
    public function loginPage(): Response
    {
        //TODO: удалить получение пользователя и поправить метод loginPage
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
}