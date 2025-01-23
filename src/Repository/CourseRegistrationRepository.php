<?php

namespace App\Repository;

use App\Entity\CourseRegistration;
use App\Entity\Qualification;
use App\Entity\User;
use App\Utils\Coll;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityRepository;

class CourseRegistrationRepository extends EntityRepository
{
    use Filterable;

    public function forUsers(Coll $users): self
    {
        $ids = $users->map(fn(User $u) => $u->getId())->toArray();

        return $this->addAndFilter('User', $ids, Comparison::IN);
    }

    public static function splitByTopic(Coll $CourseRegistrations): array
    {
        $result = [];
        $CourseRegistrations->walk(function (CourseRegistration $cR) use (&$result) {
            $tId = $cR->getTopic()->getId();
            $result[$tId] ??= Coll::create();
            $result[$tId]->add($cR);
        });

        return $result;
    }

    public static function removeAlreadyQualified(Coll $CourseRegistrations, Coll $Qualifications): Coll
    {
        return $CourseRegistrations
            ->filter(fn(CourseRegistration $cR) => !$Qualifications->exists(
                fn(int $i, Qualification $q) => $q->hasTopicAndUser($cR->getTopic(), $cR->getUser())
            )
            );
    }
}
