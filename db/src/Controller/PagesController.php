<?php
declare(strict_types=1);
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{
    #[Route('/')]
    public function number(): Response
    {
        $name = 'anonymous';

        return $this->render('authorization/login.html.twig', [
            'name' => $name,
        ]);
    }
}