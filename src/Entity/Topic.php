<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use App\Utils\Coll;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopicRepository::class), ORM\Table(name: "topics")]
class Topic 
{
    #[ORM\Id, ORM\Column(length: 5, unique: true)]
    protected string $id;

    #[ORM\Column]
    protected string $Name;
    
    #[ORM\Column]
    protected bool $NeedsBoxes = true;

    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: "Topic")]
    private Collection $Bookings;

    #[ORM\OneToMany(targetEntity: Box::class, mappedBy: "Topic")]
    private Collection $Boxes;

    public function __construct()
    {
        $this->Bookings = new Coll();
        $this->Boxes = new Coll();
    }

    public function __toString(): string
    {
        return mb_strtoupper($this->getId());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): void
    {
        $this->Name = $Name;
    }

    public function getBookings(): Collection
    {
        return $this->Bookings;
    }

    public function setBookings(Collection $Bookings): void
    {
        $this->Bookings = $Bookings;
    }

    public function getBoxes(): Collection
    {
        return $this->Boxes;
    }

    public function setBoxes(Collection $Boxes): void
    {
        $this->Boxes = $Boxes;
    }

    public function needsBoxes(): bool
    {
        return $this->NeedsBoxes;
    }

    public function setNeedsBoxes(bool $NeedsBoxes): void
    {
        $this->NeedsBoxes = $NeedsBoxes;
    }



    
    
}