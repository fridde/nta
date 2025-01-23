<?php

namespace App\Entity;

use App\Enums\UpdateType;
use App\Repository\BoxRepository;
use App\Utils\Coll;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoxRepository::class), ORM\Table(name: "boxes")]
class Box 
{
    #[ORM\Id, ORM\Column(length:8, unique: true)]
    protected string $id;


    #[ORM\ManyToOne(targetEntity: Topic::class)]
    protected Topic $Topic;

    #[Orm\ManyToMany(targetEntity: Booking::class, inversedBy: "Boxes")]
    private Collection $Bookings;

    #[ORM\OrderBy(['Date' => 'DESC'])]
    #[ORM\OneToMany(targetEntity: BoxStatusUpdate::class, mappedBy: "Box")]
    private Collection $StatusUpdates;

    private ?array $BoxParts = null;

    public function __construct()
    {
        $this->Bookings = new Coll();
        $this->StatusUpdates = new Coll();
    }

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

    public function getFormattedId(): string
    {
        $this->determineBoxParts();
        $parts = [$this->getTopic()->getId(), ...$this->BoxParts];

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
            $this->BoxParts = array_filter(self::extractBoxParts($this->id), fn($p) => $p !== null);
        }
    }

    public static function extractBoxParts(string $id): array
    {
        $pattern = "/(\D+)(\d+)(\D*)/";

        preg_match_all($pattern, $id, $matches);

        return [
            'number' => $matches[2][0],
            'letter' => $matches[3][0] ?? null,
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

    public function getTopic(): Topic
    {
        return $this->Topic;
    }

    public function setTopic(Topic $Topic): void
    {
        $this->Topic = $Topic;
    }

    public function getBookings(): Collection
    {
        return $this->Bookings;
    }

    public function hasBooking(Period $period): bool
    {
        return !$this->getBookings()
            ->filter(fn(Booking $b) => $b->getPeriod() === $period)
            ->isEmpty();
    }

    public function setBookings(Collection $Bookings): void
    {
        $this->Bookings = $Bookings;
    }

    public function addBooking(Booking $Booking): void
    {
        $this->Bookings->add($Booking);
    }

    public function getStatusUpdates(): Collection
    {
        return $this->StatusUpdates;
    }

    public function setStatusUpdates(Collection $StatusUpdates): void
    {
        $this->StatusUpdates = $StatusUpdates;
    }

    public function getLatestStatusUpdate(?UpdateType $type = null): ?BoxStatusUpdate
    {
        return (new Coll($this->getStatusUpdates()))->first();
    }





}