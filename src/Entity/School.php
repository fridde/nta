<?php

namespace App\Entity;

use App\Repository\BoxRepository;
use App\Repository\SchoolRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SchoolRepository::class), ORM\Table(name: "schools")]
class School
{
    #[ORM\Id, ORM\Column]
    protected string $id;

    #[ORM\Column]
    protected string $Name;

    #[ORM\Column]
    protected int $RouteOrder;

    #[ORM\OneToMany(mappedBy: "School", targetEntity: User::class)]
    #[ORM\OrderBy(["FirstName" => "ASC"])]
    protected Collection $Users;

    public function __construct()
    {
        $this->Users = new Collection();
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

    public function setName($Name): void
    {
        $this->Name = $Name;
    }

    public function getRouteOrder(): int
    {
        return $this->RouteOrder;
    }

    public function setRouteOrder(int $RouteOrder): void
    {
        $this->RouteOrder = $RouteOrder;
    }

}