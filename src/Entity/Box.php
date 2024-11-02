<?php

namespace App\Entity;

use App\Repository\BoxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoxRepository::class), ORM\Table(name: "boxes")]
class Box 
{
    #[ORM\Id, ORM\Column]
    protected string $id;

    #[ORM\Column]
    protected Topic $Topic;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getTopic(): Topic
    {
        return $this->Topic;
    }

    public function setTopic(Topic $Topic): void
    {
        $this->Topic = $Topic;
    }

}