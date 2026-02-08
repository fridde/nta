<?php

namespace App\Entity;

use App\Enums\UpdateType;
use App\Repository\BoxRepository;
use App\Utils\Attributes\ConvertToEntityFirst;
use App\Utils\Coll;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoxRepository::class), ORM\Table(name: "boxes")]
class Box 
{
    #[ORM\Id, ORM\Column(length:8, unique: true)]
    public string $id {
        get =>  $this->id;
    }


    #[ORM\ManyToOne(targetEntity: Topic::class)]
    #[ConvertToEntityFirst]
    public Topic $Topic;

    #[Orm\ManyToMany(targetEntity: Booking::class, inversedBy: "Boxes")]
    public Collection $Bookings;

    #[ORM\OrderBy(['Date' => 'DESC'])]
    #[ORM\OneToMany(targetEntity: BoxStatusUpdate::class, mappedBy: "Box")]
    public Collection $StatusUpdates;

    private ?array $BoxParts = null;

    public function __construct()
    {
        $this->Bookings = new Coll();
        $this->StatusUpdates = new Coll();
    }

    public function __toString(): string
    {
        return mb_strtoupper($this->id);
    }


    public function getFormattedId(): string
    {
        $this->determineBoxParts();
        $parts = [$this->Topic, ...$this->BoxParts];

        return mb_strtoupper(implode(':', $parts));
    }

    public static function standardizeId(string $id): string
    {
        $pattern = "/[\W_]/";

        return mb_strtolower(preg_replace($pattern, '', $id));
    }

    public function determineBoxParts(): void
    {
        if($this->BoxParts === null) {
            $this->BoxParts = array_filter(self::extractBoxParts($this->id), fn($p) => $p !== "");
        }
    }

    public static function extractBoxParts(string $id): array
    {
        $pattern = "/(\D+)(\d+)(\D*)/";

        preg_match_all($pattern, $id, $matches);

        return [
            'number' => $matches[2][0],
            'letter' => $matches[3][0] ?? "",
        ];
    }

    public function getBoxNumber(): string
    {
        $this->determineBoxParts();

        return $this->BoxParts['number'];
    }

    public function getBoxLetter(): ?string
    {
        $this->determineBoxParts();

        return $this->BoxParts['letter'] ?? null;
    }

    public function hasBooking(Period $period): bool
    {
        return !$this->Bookings
            ->filter(fn(Booking $b) => $b->Period === $period)
            ->isEmpty();
    }

    public function addBooking(Booking $Booking): void
    {
        $this->Bookings->add($Booking);
    }

    public function getLatestStatusUpdate(?UpdateType $type = null): ?BoxStatusUpdate
    {
        return new Coll($this->StatusUpdates)->first();
    }





}