<?php

namespace App\Repository;

use App\Entity\Period;
use App\Enums\Semester;
use App\Utils\Coll;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;

class PeriodRepository extends EntityRepository
{
    public function getFuturePeriods(): Coll
    {
        $currentPeriod = $this->getCurrentPeriod();

        return Coll::create($this->findAll())
            ->filter(fn(Period $p) => $p->compare($currentPeriod) > 0);
    }

    public function getCurrentPeriod(): Period
    {
        $now = Carbon::now();
        $id = $now->year . '.' . Semester::getSemesterForDate($now)->value;

        return $this->find($id);
    }

    public function getFormattedPeriods(): array
    {
        $result = [];
        foreach ($this->findAll() as $period) {
            /** @var Period $period */
            $result[$period->getId()] = $period;
        }

        return $result;
    }


}
