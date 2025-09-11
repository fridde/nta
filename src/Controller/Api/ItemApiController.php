<?php

namespace App\Controller\Api;

use App\Entity\Item;
use App\Entity\User;
use App\Enums\Role;
use App\Security\Voter\SameSchoolVoter;
use App\Utils\Attributes\ConvertToEntityFirst;
use App\Utils\RepoContainer;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionNamedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ItemApiController extends AbstractController
{
    private ?Request $request;
    private EntityManagerInterface $em;

    public function __construct(
        RequestStack $requestStack,
        private readonly RepoContainer $rc,
        private readonly Security $security
    )
    {
        $this->em = $rc->getEntityManager();
        $this->request = $requestStack->getCurrentRequest();
    }

    #[Route(
        '/api/item/{item}',
        methods: ['GET']
    )]
    public function getItem(Item $item): JsonResponse
    {

        //$this->rc->getItemRepo()->get;

        return $this->asJson(['item' => $item->getMostSimpleLabel()]);
    }

    #[Route(
        '/api/items/{itemString}',
        name: 'api_get_items',
        methods: ['GET']
    )]
    public function getMultipleItems(string $itemString): JsonResponse
    {
        $itemIds = explode(',', $itemString);


        $items = $this->rc->getItemRepo()->getItems($itemIds)
            ->map(fn(Item $i) => [
                'id' => $i->id,
                'placement' => $i->Placement,
                'detailed_label' => $i->DetailedLabel,
                'simple_label' => $i->SimpleLabel,
                'staff_info' => $i->StaffInfo,
            ])
            ->toArray();

        return $this->asJson($items);

    }



    private function asJson($data, int $status = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse((array) $data, $status);
    }

}