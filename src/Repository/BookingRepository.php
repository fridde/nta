<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\Period;
use App\Entity\School;
use App\Utils\ExtendedCollection;
use Doctrine\ORM\EntityRepository;


class BookingRepository extends EntityRepository
{
    use Filterable;

    public function hasPeriod(Period $period): self
    {
        $this->addAndFilter('Period', $period->getId());
    }

    public function getBookingsFromSchool(School $school): ExtendedCollection
    {
        return ExtendedCollection::create($this->findAll())
            ->filter(fn(Booking $b) => $b->getBoxOwner()->hasSchool($school));
    }

    public function getBookingsForPeriod(Period $period): ExtendedCollection
    {
        return $this->hasPeriod($period)->getMatching();
    }

    public function compileBoxOccupancyByTopicForPeriod(Period $period): array
    {
        // TODO: implement this
    }
}
