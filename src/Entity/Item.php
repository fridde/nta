<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use App\Utils\Coll;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    private const string ID_SYMBOLS = '2346789abcdefghjklmnpqrtuvwxyz';

    #[ORM\Id]
    #[ORM\Column(length: 3, unique: true)]
    public string $id {
        get => $this->id;
        set {
            $this->id = mb_strtolower(trim($value));
        }
    }

    #[ORM\Column(nullable: true)]
    public ?string $Placement = null;

    #[ORM\Column(nullable: true)]
    public ?string $DetailedLabel = null;

    #[ORM\Column(nullable: true)]
    public ?string $SimpleLabel = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    public ?array $StaffInfo = null;

    #[ORM\Column(nullable: true)]
    public ?string $UserInfo = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    public ?array $OrderInfo = null;

    #[ORM\OneToMany(targetEntity: InventoryStatusUpdate::class, mappedBy: 'Item')]
    public Collection $InventoryStatusUpdates;

    public function __construct()
    {
        $this->InventoryStatusUpdates = new Coll();
    }

    public function createId(): string
    {
        $id = '';
        $max = strlen(self::ID_SYMBOLS) - 1;

        for ($i = 0; $i < 3; $i++) {
            $id .= self::ID_SYMBOLS[random_int(0, $max)];
        }

        return $id;
    }

    public function getMostSimpleLabel(): string
    {
        $label = $this->SimpleLabel;
        if (empty($label)) {
            return $this->DetailedLabel;
        }
        return $label;
    }


}
