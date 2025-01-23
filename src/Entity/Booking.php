<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use App\Utils\ExtendedCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'Bookings')]
    private User $BoxOwner;

    #[ORM\ManyToOne(targetEntity: Period::class, inversedBy: 'Bookings')]
    private Period $Period;

    #[ORM\ManyToOne(targetEntity: Topic::class, inversedBy: 'Bookings')]
    private Topic $Topic;

    #[ORM\Column]
    private int $NrBoxes = 0;

    #[ORM\Column]
    private int $NrStudents = 30;

    #[ORM\ManyToOne(targetEntity:User::class)]
    private ?User $Booker = null;

    #[ORM\ManyToMany(targetEntity: Box::class, mappedBy: 'Bookings')]
    private Collection $Boxes;

    public function __construct()
    {
        $this->Boxes = new ExtendedCollection();
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoxOwner(): User
    {
        return $this->BoxOwner;
    }

    public function setBoxOwner(User $BoxOwner): void
    {
        $this->BoxOwner = $BoxOwner;
    }

    public function getPeriod(): Period
    {
        return $this->Period;
    }

    public function setPeriod(Period $Period): void
    {
        $this->Period = $Period;
    }

    public function getTopic(): Topic
    {
        return $this->Topic;
    }

    public function setTopic(Topic $Topic): void
    {
        $this->Topic = $Topic;
    }

    public function getNrBoxes(): int
    {
        return $this->NrBoxes;
    }

    public function setNrBoxes(int $NrBoxes): void
    {
        $this->NrBoxes = $NrBoxes;
    }

    public function getNrStudents(): int
    {
        return $this->NrStudents;
    }

    public function setNrStudents(int $NrStudents): void
    {
        $this->NrStudents = $NrStudents;
    }

    public function getBooker(): ?User
    {
        return $this->Booker;
    }

    public function setBooker(?User $Booker): void
    {
        $this->Booker = $Booker;
    }

    public function getBoxes(): Collection
    {
        return $this->Boxes;
    }

    public function setBoxes(Collection $Boxes): void
    {
        $this->Boxes = $Boxes;
    }

}
