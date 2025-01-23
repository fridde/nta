<?php

namespace App\Repository;

use App\Entity\User;
use App\Utils\ExtendedCollection;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityRepository;


class QualificationRepository extends EntityRepository
{
    use Filterable;

    public function forUsers(ExtendedCollection $users): self
    {
        $ids = $users->map(fn(User $u) => $u->getId())->toArray();

        return $this->addAndFilter('User', $ids, Comparison::IN);
    }
}
