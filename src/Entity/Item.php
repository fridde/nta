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
    private string $id;

    #[ORM\Column(nullable: true)]
    private ?string $Placement = null;

    #[ORM\Column(nullable: true)]
    private ?string $DetailedLabel = null;

    #[ORM\Column(nullable: true)]
    private ?string $SimpleLabel = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $StaffInfo = null;

    #[ORM\Column(nullable: true)]
    private ?string $UserInfo = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $OrderInfo = null;

    #[ORM\OneToMany(targetEntity: InventoryStatusUpdate::class, mappedBy: 'Item')]
    private Collection $InventoryStatusUpdates;

    public function __construct()
    {
        $this->InventoryStatusUpdates = new Coll();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = mb_strtolower(trim($id));
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

    public function getPlacement(): ?string
    {
        return $this->Placement;
    }

    public function setPlacement(?string $Placement): void
    {
        $this->Placement = $Placement;
    }

    public function getDetailedLabel(): ?string
    {
        return $this->DetailedLabel;
    }

    public function setDetailedLabel(?string $DetailedLabel): void
    {
        $this->DetailedLabel = $DetailedLabel;
    }

    public function getSimpleLabel(): ?string
    {
        return $this->SimpleLabel;
    }

    public function setSimpleLabel(?string $SimpleLabel): void
    {
        $this->SimpleLabel = $SimpleLabel;
    }

    public function getMostSimpleLabel(): string
    {
        $label = $this->getSimpleLabel();
        if (empty($label)) {
            return $this->getDetailedLabel();
        }
        return $label;
    }

    public function getStaffInfo(): ?array
    {
        return $this->StaffInfo;
    }

    public function setStaffInfo(?array $StaffInfo): void
    {
        $this->StaffInfo = $StaffInfo;
    }

    public function getUserInfo(): ?string
    {
        return $this->UserInfo;
    }

    public function setUserInfo(?string $UserInfo): void
    {
        $this->UserInfo = $UserInfo;
    }

    public function getOrderInfo(): ?array
    {
        return $this->OrderInfo;
    }

    public function setOrderInfo(?array $OrderInfo): void
    {
        $this->OrderInfo = $OrderInfo;
    }

    public function getInventoryStatusUpdates(): Collection
    {
        return $this->InventoryStatusUpdates;
    }

    public function setInventoryStatusUpdates(Collection $InventoryStatusUpdates): void
    {
        $this->InventoryStatusUpdates = $InventoryStatusUpdates;
    }


}
