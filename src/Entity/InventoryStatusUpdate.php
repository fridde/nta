<?php

namespace App\Entity;

use App\Repository\InventoryStatusUpdateRepository;
use App\Utils\Attributes\ConvertToEntityFirst;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoryStatusUpdateRepository::class)]
class InventoryStatusUpdate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null {
        get =>  $this->id;
    }

    #[ORM\ManyToOne(targetEntity: Item::class, inversedBy: 'InventoryStatusUpdates')]
    #[ConvertToEntityFirst]
    public Item $Item;

    #[ORM\Column]
    public bool $Sufficient = false;

    #[ORM\Column]
    public \DateTime $Date;

}
