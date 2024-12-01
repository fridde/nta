<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: "topics")]
class Topic 
{
    #[ORM\Id, ORM\Column(length: 5, unique: true)]
    protected string $id;

    #[ORM\Column]
    protected string $Name;

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

}