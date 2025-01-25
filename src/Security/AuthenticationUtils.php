<?php

namespace App\Security;

use App\Entity\User;
use App\Security\Key\ApiKeyManager;
use App\Security\Key\Key;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;

class AuthenticationUtils
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ApiKeyManager $akm
    )
    {
    }

    public function getUserFromKey(Key $key): ?User
    {
        return $this->em->find(User::class, $key->id);
    }

    public function createCookieWithAuthKey(User $user, Carbon $expDate = null): Cookie
    {
        $key = $this->akm->createKeyFromValues($user->getId());

        return Cookie::create('key')
            ->withValue($this->akm->createCodeStringForKey($key))
            ->withExpires($expDate ?? Carbon::today()->addDays(90))
            ->withHttpOnly(false)
            ->withSecure();
    }

    public function createUrlKeyStringForUser(User $user): string
    {
        $key = $this->akm->createKeyFromValues($user->getId());

        return $this->akm->createCodeStringForKey($key);
    }
}