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
    public ?int $id;

    #[ORM\Column]
    public UpdateType $Type;

    #[ORM\Column]
    public \DateTime $Date;

    #[ORM\ManyToOne(targetEntity: Box::class, inversedBy: "StatusUpdates")]
    public Box $Box;

}
