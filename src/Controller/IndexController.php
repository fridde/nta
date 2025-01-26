<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $number = random_int(0, 100);

        return new Response('<html><body>Lucky number: '.$number.'</body></html>');
    }

    #[Route('/logout', name:'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        // Nothing in this method will be executed, it's just there to satisfy the router
        // ALl logic is executed in LogoutSubscriber
    }

}
