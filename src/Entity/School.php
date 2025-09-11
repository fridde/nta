<?php

namespace App\Entity;

use App\Repository\BoxRepository;
use App\Repository\SchoolRepository;
use App\Utils\Coll;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SchoolRepository::class), ORM\Table(name: "schools")]
class School
{
    #[ORM\Id, ORM\Column(length: 8, unique: true)]
    public string $id;

    #[ORM\Column]
    public string $Name;

    #[ORM\Column]
    public int $RouteOrder;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: "School")]
    #[ORM\OrderBy(["FirstName" => "ASC"])]
    public Collection $Users;

    public function __construct()
    {
        $this->Users = new Coll();
    }

    public function __toString(): string
    {
        return mb_strtoupper($this->id);
    }

    public function equals(School $school): bool
    {
        return $this->id === $school->id;
    }



}