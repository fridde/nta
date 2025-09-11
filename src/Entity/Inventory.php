<?php

namespace App\Entity;

use App\Enums\InventoryType;
use App\Repository\InventoryRepository;
use Doctrine\ORM\Mapping as ORM;
use function Symfony\Component\Translation\t;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\Column]
    public InventoryType $InventoryType;

    #[ORM\ManyToOne(targetEntity: Item::class)]
    public Item $Item;

    #[ORM\Column]
    public int $Quantity;

    #[ORM\ManyToOne(targetEntity: Topic::class)]
    public ?Topic $Topic = null;

    #[ORM\Column]
    public int $ListRank = 0 {
        get => $this->ListRank;
        set(?int $value) {
            $this->ListRank = $value ?? 0;
        }
    }

    public function __construct(null|InventoryType $inventoryType = null)
    {
        if($inventoryType instanceof InventoryType) {
            $this->InventoryType = $inventoryType;
        }
    }


}
