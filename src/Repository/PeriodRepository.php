<?php

namespace App\Repository;

use App\Entity\Period;
use App\Enums\Semester;
use App\Utils\ExtendedCollection;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;

class PeriodRepository extends EntityRepository
{
    public function getFuturePeriods(): ExtendedCollection
    {
        $currentPeriod = $this->getCurrentPeriod();

        return ExtendedCollection::create($this->findAll())
            ->filter(fn(Period $p) => $p->compare($currentPeriod) > 0);
    }

    public function getCurrentPeriod(): Period
    {
        $now = Carbon::now();
        $id = $now->year . '.' . Semester::getSemesterForDate($now)->value;

        return $this->find($id);
    }


}
