<?php

namespace App\Entity;

use App\Enums\UpdateType;
use App\Repository\BoxStatusUpdateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoxStatusUpdateRepository::class)]
class BoxStatusUpdate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]    
    private UpdateType $Type;

    #[ORM\Column]
    private \DateTime $Date;
    
    #[ORM\ManyToOne(targetEntity: Box::class, inversedBy: "StatusUpdates")]
    private Box $Box;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): UpdateType
    {
        return $this->Type;
    }

    public function setType(UpdateType $Type): void
    {
        $this->Type = $Type;
    }

    public function getDate(): \DateTime
    {
        return $this->Date;
    }

    public function setDate(\DateTime $Date): void
    {
        $this->Date = $Date;
    }

    public function getBox(): Box
    {
        return $this->Box;
    }

    public function setBox(Box $Box): void
    {
        $this->Box = $Box;
    }





}
