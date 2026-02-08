<?php

namespace App\Entity;

use App\Enums\Role;
use App\Utils\Attributes\ConvertToEntityFirst;
use App\Utils\Coll;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class), ORM\Table(name: "users")]
class User implements UserInterface
{

    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    public int $id;

    #[ORM\Column(nullable: true)]
    public ?string $FirstName;

    #[ORM\Column(nullable: true)]
    public ?string $LastName;

    #[ORM\Column(unique: true)]
    public string $Mail {
        get => $this->Mail;
        set(?string $value) {
            $this->Mail = mb_strtolower(trim($value));
        }
    }

    #[ORM\Column(type: Types::JSON)]
    public array $Roles = [] {
        get {
            $roles = Coll::create($this->Roles);
            $roles->add(Role::USER->value);

            return $roles->unique()->toArray();
        }
        set(array $rolesAsStrings) {
            $roles = Coll::create($rolesAsStrings);
            $roles->removeElement(Role::USER->value);

            $this->Roles = $roles->toArray();
        }
    }

    #[ORM\ManyToOne(targetEntity: School::class, inversedBy: "Users")]
    #[ConvertToEntityFirst]
    public School $School;

    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: "BoxOwner")]
    public Collection $Bookings;

    #[ORM\OneToMany(targetEntity: Qualification::class, mappedBy: "User")]
    public Collection $Qualifications;

    public string $FullName {
        get => $this->FirstName . ' ' . $this->LastName;
    }

    public function __construct()
    {
        $this->Bookings = new Coll();
    }

    public function __toString(): string
    {
        return $this->FullName . ' [' .  strtoupper($this->School) . ']';
    }

    public function hasSchool(School $school): bool
    {
        return $this->School->equals($school);
    }

    public function getQualificationsAsArray(): array
    {
        $keys = $this->Qualifications
            ->map(fn(Qualification $q) => $q->Topic)
            ->toArray();
        return array_combine($keys, $this->Qualifications->toArray());
    }

    public function addQualification(Qualification $Qualification): void
    {
        $this->Qualifications->add($Qualification);
    }

    public function addRole(Role $role): void
    {
        $roles = Coll::create($this->Roles);
        $roles->add($role->value);

        $this->Roles = $roles->unique()->toArray();
    }

    // methods below required by UserInterface
    public function getRoles(): array
    {
        return $this->Roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->Mail;
    }


}