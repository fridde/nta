<?php

namespace App\Entity;

use App\Utils\Coll;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class), ORM\Table(name: "users")]
class User
{

    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected int $id;

    #[ORM\Column(nullable: true)]
    protected ?string $FirstName;

    #[ORM\Column(nullable: true)]
    protected ?string $LastName;

    #[ORM\Column(unique: true)]
    protected string $Mail;

    #[ORM\ManyToOne(targetEntity: School::class, inversedBy: "Users")]
    protected School $School;

    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: "BoxOwner")]
    protected Collection $Bookings;

    #[ORM\OneToMany(targetEntity: Qualification::class, mappedBy: "User")]
    protected Collection $Qualifications;

    public function __construct()
    {
        $this->Bookings = new Coll();
    }

    public function __toString(): string
    {
        return $this->FirstName . ' ' . $this->LastName . ' [' .  strtoupper($this->School->getId()) . ']';
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(?string $FirstName): void
    {
        $this->FirstName = $FirstName;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(?string $LastName): void
    {
        $this->LastName = $LastName;
    }

    public function getFullName(): string
    {
        return $this->FirstName . ' ' . $this->LastName;
    }

    public function getMail(): ?string
    {
        return $this->Mail;
    }

    public function setMail(?string $Mail): void
    {
        $this->Mail = mb_strtolower(trim($Mail));
    }

    public function hasSchool(School $school): bool
    {
        return $this->getSchool()->equals($school);
    }

    public function getSchool(): School
    {
        return $this->School;
    }

    public function setSchool(School $School): void
    {
        $this->School = $School;
    }

    public function getBookings(): Collection
    {
        return $this->Bookings;
    }

    public function setBookings(Collection $Bookings): void
    {
        $this->Bookings = $Bookings;
    }

    public function getQualifications(): Collection
    {
        return $this->Qualifications;
    }

    public function getQualificationsAsArray(): array
    {
        $keys = $this->getQualifications()
            ->map(fn(Qualification $q) => $q->getTopic()->getId())
            ->toArray();
        return array_combine($keys, $this->getQualifications()->toArray());
    }

    public function setQualifications(Collection $Qualifications): void
    {
        $this->Qualifications = $Qualifications;
    }

    public function addQualification(Qualification $Qualification): void
    {
        $this->Qualifications->add($Qualification);
    }




}