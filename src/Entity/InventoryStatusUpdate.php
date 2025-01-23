<?php

namespace App\Entity;

use App\Repository\InventoryStatusUpdateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoryStatusUpdateRepository::class)]
class InventoryStatusUpdate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Item::class, inversedBy: 'InventoryStatusUpdates')]
    private Item $Item;

    #[ORM\Column]
    private bool $Sufficient = false;

    #[ORM\Column]
    private \DateTime $Date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): Item
    {
        return $this->Item;
    }

    public function setItem(Item $Item): void
    {
        $this->Item = $Item;
    }

    public function isSufficient(): bool
    {
        return $this->Sufficient;
    }

    public function setSufficient(bool $Sufficient): void
    {
        $this->Sufficient = $Sufficient;
    }

    public function getDate(): \DateTime
    {
        return $this->Date;
    }

    public function setDate(\DateTime $Date): void
    {
        $this->Date = $Date;
    }


}
