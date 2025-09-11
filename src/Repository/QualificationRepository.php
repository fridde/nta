<?php

namespace App\Repository;

use App\Entity\User;
use App\Utils\Coll;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityRepository;


class QualificationRepository extends EntityRepository
{
    use Filterable;

    public function forUsers(Coll $users): self
    {
        $ids = $users->map(fn(User $u) => $u->id)->toArray();

        return $this->addAndFilter('User', $ids, Comparison::IN);
    }
}
