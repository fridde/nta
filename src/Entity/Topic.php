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
    public string $id;

    #[ORM\Column]
    public string $Name;

    #[ORM\Column]
    public bool $NeedsBoxes = true;

    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: "Topic")]
    public Collection $Bookings;

    #[ORM\OneToMany(targetEntity: Box::class, mappedBy: "Topic")]
    public Collection $Boxes;

    public function __construct()
    {
        $this->Bookings = new Coll();
        $this->Boxes = new Coll();
    }

    public function __toString(): string
    {
        return mb_strtoupper($this->id);
    }

}