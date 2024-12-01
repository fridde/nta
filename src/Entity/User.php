<?php

namespace App\Entity;

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

    #[ORM\ManyToOne(targetEntity: School::class)]
    protected School $School;

    protected Collection $Bookings;

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

    public function getMail(): ?string
    {
        return $this->Mail;
    }

    public function setMail(?string $Mail): void
    {
        $this->Mail = mb_strtolower(trim($Mail));
    }

    public function getSchool(): School
    {
        return $this->School;
    }

    public function setSchool(School $School): void
    {
        $this->School = $School;
    }


}