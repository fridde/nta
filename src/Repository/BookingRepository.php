<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\Period;
use App\Entity\School;
use App\Utils\Coll;
use Doctrine\ORM\EntityRepository;


class BookingRepository extends EntityRepository
{
    use Filterable;

    public function hasPeriod(Period $period): self
    {
        return $this->addAndFilter('Period', $period->getId());
    }

    public function getBookingsFromSchool(School $school): Coll
    {
        return Coll::create($this->findAll())
            ->filter(fn(Booking $b) => $b->getBoxOwner()->hasSchool($school));
    }

    public function getBookingsForPeriod(Period $period): Coll
    {
        return $this->hasPeriod($period)->getMatching();
    }

    public function compileBoxOccupancyByTopicForPeriod(Period $period): array
    {
        // TODO: implement this
    }

    public static function splitByPeriod(Coll $Bookings): array
    {
        $result = [];
        $Bookings->walk(function(Booking $b) use(&$result) {
            $pId = $b->getPeriod()->getId();
            $result[$pId] ??= Coll::create();
            $result[$pId]->add($b);
        });

        return $result;
    }
}
