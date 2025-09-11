<?php

namespace App\Controller\Api;

use App\Entity\Box;
use App\Entity\BoxStatusUpdate;
use App\Entity\Inventory;
use App\Entity\Topic;
use App\Enums\UpdateType;
use App\Utils\RepoContainer;
use Carbon\Carbon;
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
        RequestStack                   $requestStack,
        private readonly RepoContainer $rc,
        private readonly Security      $security
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
        $boxRepo = $this->rc->getBoxRepo();

        foreach ($boxes as $boxId) {
           $bsu =  new BoxStatusUpdate();
           $box = $boxRepo->find(Box::standardizeId($boxId));
           //assertInstanceOf(Box::class, $box, sprintf('Box %s not found', $boxId));
           $bsu->Box = $box;
           $bsu->Type = $updateType;
           $bsu->Date = Carbon::now();
           $this->em->persist($bsu);
        }
        $this->em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/api/get-inventory-list/{topic}')]
    public function getInventoryList(Topic $topic): JsonResponse
    {
        $data = $this->rc->getInventoryRepo()->getInventoryForTopic($topic);
        $rows = $data->map(fn(Inventory $i) => [
            'desc' => $i->Item->getMostSimpleLabel(),
            'amount' => $i->Quantity,
            'comment' => $i->Item->UserInfo,
            'counted' => str_contains(mb_strtolower($i->Item->UserInfo), 'räknas'),
        ])->toArray();

        return new JsonResponse(['rows' => $rows]);
    }

}