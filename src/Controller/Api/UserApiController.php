<?php

namespace App\Controller\Api;

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

class UserApiController extends AbstractController
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

    #[Route(
        '/api/user/{user}',
        methods: ['POST']
    )]
    #[IsGranted(SameSchoolVoter::class)]
    public function updateUser(User $user): JsonResponse
    {

        $this->updateEntityData($user);

        return $this->asJson(['success' => true]);
    }

    #[Route(
        '/api/create/user',
        methods: ['GET', 'POST']
    )]
    #[IsGranted(Role::ACTIVE_USER->value)]
    public function createUser(): JsonResponse|Response
    {
        /** @var User $requestingUser  */
        $requestingUser = $this->security->getUser();

        $data = $this->request->getPayload()->all('user_data');

        $tempId = $data['id'];
        unset($data['id']);
        $user = new User();
        $this->updateSingleEntity($user, $data);

        $isAdmin = $this->security->isGranted(Role::ADMIN->value);

        if(!($isAdmin || $requestingUser->hasSchool($user->School))){
            throw $this->createAccessDeniedException();
        }
        $user->addRole(Role ::ACTIVE_USER);
        try {
            $this->em->flush();
        } catch(UniqueConstraintViolationException $ue){
            $msg = 'En användare med mejladress "%s" finns redan i databasen och tillhör %s. ';
            $msg .= 'Kontakta oss ifall detta bör ändras!';

            return $this->asJson([
                'error' => sprintf($msg, $user->Mail, $user->School->getName())
            ], Response::HTTP_BAD_REQUEST);
        }

//        if($isAdmin){
//            return new Response('<h1>Användare har lagts till</h1>');
//        }

        return $this->asJson(['temp_id' => $tempId, 'user_id' => $user->id]);
    }

    private function asJson($data, int $status = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse((array) $data, $status);
    }

    public function updateSingleEntity($e, array $data = []): void
    {
        foreach ($data as $attribute => $newValue) {
            $setMethod = 'set' . ucfirst($attribute);
            $newValue = $this->convertToEntityIfNecessary($e, $setMethod, $newValue);
            $e->$setMethod($newValue);
        }
        $this->em->persist($e);
    }

    private function updateEntityData($e, ?array $data = null): void
    {
        $x = $this->request->getPayload();
        $data ??= $this->request->getPayload()->all('user_data');
        $this->updateSingleEntity($e, $data);
        $this->em->flush();
    }


    private function convertToEntityIfNecessary($e, string $setMethod, $value): mixed
    {
        $reflector = new \ReflectionClass($e);
        $method = $reflector->getMethod($setMethod);
        $attributes = $method->getAttributes(ConvertToEntityFirst::class);
        if (empty($attributes)) {
            return $value;
        }
        $parameter = $method->getParameters()[0];
        /** @var ReflectionNamedType $type */
        $type = $parameter->getType();

        if (is_object($value) && \str_ends_with(get_class($value), $type->getName())) {  // this covers both the FQCN and the short version
            return $value;
        }

        $entity = $this->em->find($type->getName(), $value);
        if (empty($entity)) {
            throw new \RuntimeException('An entity of the type ' . $type->getName() . ' and id ' . $value . ' does not exist');
        }

        return $entity;
    }

}