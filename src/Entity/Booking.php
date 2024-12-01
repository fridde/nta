<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'Bookings')]
    private User $boxOwner;

    #[ORM\ManyToOne(targetEntity: Period::class, inversedBy: 'Bookings')]
    private Period $period;

    #[ORM\ManyToOne(targetEntity:Topic::class, inversedBy: 'Bookings')]
    private Topic $topic;

    #[ORM\Column]
    private int $nrBoxes = 0;

    #[ORM\Column]
    private int $nrStudents = 30;

    #[ORM\ManyToOne(targetEntity:Topic::class)]
    private ?User $booker = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
