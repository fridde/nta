<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use App\Utils\Coll;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'Bookings')]
    public User $BoxOwner;

    #[ORM\ManyToOne(targetEntity: Period::class, inversedBy: 'Bookings')]
    public Period $Period;

    #[ORM\ManyToOne(targetEntity: Topic::class, inversedBy: 'Bookings')]
    public Topic $Topic;

    #[ORM\Column]
    public int $NrBoxes = 0;

    #[ORM\Column]
    public int $NrStudents = 30;

    #[ORM\ManyToOne(targetEntity:User::class)]
    public ?User $Booker = null;

    #[ORM\ManyToMany(targetEntity: Box::class, mappedBy: 'Bookings')]
    private Collection $Boxes;

    public function __construct()
    {
        $this->Boxes = new Coll();
    }

    public function addBox(Box $box): void
    {
        $this->Boxes->add($box);
    }

}
