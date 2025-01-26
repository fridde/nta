<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class AzureAuthController extends AbstractController
{
    #[Route("/connect/azure", name: "connect_via_azure")]
    public function connectToAzure(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry->getClient('azure')->redirect([], []);
    }

    #[Route("/connect/azure/check", name: "connect_azure_check")]
    #[Template('base.html.twig')]
    public function checkAzureLogin(): void
    {
// The AzureAuthController will check for this Route ("connect_azure_check")
// and redirect to the original request route
    }
}