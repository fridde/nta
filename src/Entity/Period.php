<?php

namespace App\Entity;

use App\Enums\Semester;
use App\Repository\PeriodRepository;
use App\Utils\Coll;
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
    public string $id;

    #[ORM\Column]
    public DateTime $StartDate {
        get => $this->StartDate;
        set(DateTime|string $value) {
            if (is_string($value)) {
                $value = new Carbon($value);
            }
            $this->StartDate = $value;
        }
    }

    #[ORM\Column]
    public DateTime $EndDate {
        get => $this->EndDate;
        set(DateTime|string $value) {
            if (is_string($value)) {
                $value = new Carbon($value);
            }
            $this->EndDate = $value;
        }
    }

    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: "Period")]
    public Collection $Bookings;

    public function __construct(\DateTime|null $fromDate = null)
    {
        if($fromDate instanceof DateTime) {
            $date = Carbon::instance($fromDate);
            $semester = Semester::getSemesterForDate($date);
            $this->id = $date->year . '.' . $semester->value;
        }
        $this->Bookings = new Coll();
    }

    public function __toString(): string
    {
        return $this->getSemester()->name . ' ' . $this->getYear();
    }

    public function equals(Period $period): bool
    {
        return $this->id === $period->id;
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

    public function isCurrent(): bool
    {
        $period = new Period(Carbon::now());

        return $this->equals($period);
    }

    public function isNext(): bool
    {
        $period = new Period(Carbon::now()->addDays(180));

        return $this->equals($period);
    }

    public function isCurrentOrNext(): bool
    {
        return $this->isCurrent() || $this->isNext();
    }


}
