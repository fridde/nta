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
    private ?int $id = null;

    #[ORM\Column]
    private InventoryType $InventoryType;

    #[ORM\ManyToOne(targetEntity: Item::class)]
    private Item $Item;

    #[ORM\Column]
    private int $Quantity;

    #[ORM\ManyToOne(targetEntity: Topic::class)]
    private ?Topic $Topic = null;

    #[ORM\Column]
    private int $ListRank = 0;

    public function __construct(null|InventoryType $inventoryType = null)
    {
        if($inventoryType instanceof InventoryType) {
            $this->InventoryType = $inventoryType;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getInventoryType(): InventoryType
    {
        return $this->InventoryType;
    }

    public function setInventoryType(InventoryType $InventoryType): void
    {
        $this->InventoryType = $InventoryType;
    }

    public function getItem(): Item
    {
        return $this->Item;
    }

    public function setItem(Item $Item): void
    {
        $this->Item = $Item;
    }

    public function getQuantity(): int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): void
    {
        $this->Quantity = $Quantity;
    }

    public function getTopic(): ?Topic
    {
        return $this->Topic;
    }

    public function setTopic(?Topic $Topic): void
    {
        $this->Topic = $Topic;
    }

    public function getListRank(): ?int
    {
        return $this->ListRank;
    }

    public function setListRank(?int $ListRank): void
    {
        $this->ListRank = $ListRank ?? 0;
    }


}
