<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookingFormController extends AbstractController
{
    public function __construct(
    )
    {
    }

    #[Route('/boka/box')]
    public function bookBox(): Response
    {
        //$booking

        $this->createFormBuilder();


        return $this->render('booking/index.html.twig');
    }
}
