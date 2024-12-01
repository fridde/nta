<?php

namespace App\Entity;

use App\Enums\Semester;
use App\Repository\PeriodRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeriodRepository::class)]
class Period
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $year;

    #[ORM\Column]
    private Semester $semester;

    #[ORM\Column]
    private ?Carbon $startDate = null;

    #[ORM\Column]
    private ?Carbon $endDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

}
