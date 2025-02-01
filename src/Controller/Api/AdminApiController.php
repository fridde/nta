<?php

namespace App\Controller\Api;

use App\Enums\UpdateType;
use App\Utils\RepoContainer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

class AdminApiController extends AbstractController
{
    private ?Request $request;
    private EntityManagerInterface $em;

    public function __construct(
        RequestStack $requestStack,
        RepoContainer $rc,
        private readonly Security $security
    )
    {
        $this->em = $rc->getEntityManager();
        $this->request = $requestStack->getCurrentRequest();
    }


    #[Route('/api/batch/box-status')]
    public function batchUpdateBoxStatus(): JsonResponse
    {
        $updateType = $this->request->getPayload()->get('updateType');
        $updateType = UpdateType::from($updateType);

        $boxes = $this->request->getPayload()->get('boxes');
        $boxes = array_map("trim", explode(PHP_EOL, $boxes));

        // TODO: continue here
    }

}