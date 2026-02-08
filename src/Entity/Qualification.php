<?php

namespace App\Entity;

use App\Repository\QualificationRepository;
use App\Utils\Attributes\ConvertToEntityFirst;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QualificationRepository::class)]
class Qualification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'Qualifications')]
    #[ConvertToEntityFirst]
    public User $User;

    #[ORM\ManyToOne(targetEntity: Topic::class)]
    #[ConvertToEntityFirst]
    public Topic $Topic;

    #[ORM\Column]
    public \DateTime $Date;

    public function hasTopicAndUser(Topic $Topic, User $User): bool
    {
        $sameTopic = $this->Topic->id === $Topic->id;

        return $sameTopic && $this->User->id === $User->id;
    }
}
