<?php

namespace App\Entity;

use App\Enums\Semester;
use App\Repository\PeriodRepository;
use App\Utils\ExtendedCollection;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeriodRepository::class)]
class Period
{
    #[ORM\Id]
    #[ORM\Column(length: 8, unique: true)]
    private string $id;

    #[ORM\Column]
    private DateTime $StartDate;

    #[ORM\Column]
    private DateTime $EndDate;

    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: "Period")]
    private Collection $Bookings;

    public function __construct()
    {
        $this->Bookings = new ExtendedCollection();
    }

    public function __toString(): string
    {
        return $this->getSemester()->name . ' ' . $this->getYear();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getYear(): int
    {
        return (int) explode('.', $this->id)[0];
    }

    public function getSemester(): Semester
    {
        $part = (int) explode('.', $this->id)[1];

        return Semester::from($part);
    }

    public function compare(self $period): int
    {
        $yearComparison = $this->getYear() <=> $period->getYear();
        if($yearComparison !== 0) {
            return $yearComparison;
        }
        return $this->getSemester()->compare($period->getSemester());
    }

    public function getStartDate(): DateTime|Carbon
    {
        return $this->StartDate;
    }

    public function setStartDate(DateTime|string $StartDate): void
    {
        if(is_string($StartDate)) {
            $StartDate = new Carbon($StartDate);
        }
        $this->StartDate = $StartDate;
    }

    public function getEndDate(): DateTime|Carbon
    {
        return $this->EndDate;
    }

    public function setEndDate(DateTime|string $EndDate): void
    {
        if(is_string($EndDate)) {
            $EndDate = new Carbon($EndDate);
        }
        $this->EndDate = $EndDate;
    }

    public function getBookings(): Collection
    {
        return $this->Bookings;
    }

    public function setBookings(Collection $Bookings): void
    {
        $this->Bookings = $Bookings;
    }


}
