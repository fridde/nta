<?php

namespace App\Security\Voter;

use App\Entity\Booking;
use App\Entity\CourseRegistration;
use App\Entity\School;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SameSchoolVoter extends Voter
{
    public function __construct(
        private readonly Security $security
    )
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::class;
    }


    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        return $this->hasRightSchool($user, $subject);
    }

    private function hasRightSchool(
        User $user,
        School|User|Booking|CourseRegistration $subject
    ): bool
    {
        $userSchool = $user->getSchool();

        return match(true){
            $subject instanceof School => $subject->equals($userSchool),
            $subject instanceof Booking => $subject->getBoxOwner()->hasSchool($userSchool),
            $subject instanceof User => $subject->hasSchool($userSchool),
            $subject instanceof CourseRegistration => $subject->getUser()->hasSchool($userSchool),
        };
    }
}