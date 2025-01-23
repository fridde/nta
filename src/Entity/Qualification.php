<?php

namespace App\Entity;

use App\Repository\QualificationRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QualificationRepository::class)]
class Qualification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'Qualifications')]
    private User $User;

    #[ORM\ManyToOne(targetEntity: Topic::class)]
    private Topic $Topic;

    #[ORM\Column]
    private \DateTime $Date;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->User;
    }

    public function setUser(User $User): void
    {
        $this->User = $User;
    }

    public function getTopic(): Topic
    {
        return $this->Topic;
    }

    public function setTopic(Topic $Topic): void
    {
        $this->Topic = $Topic;
    }

    public function getDate(): \DateTime
    {
        return $this->Date;
    }

    public function setDate(\DateTime $Date): void
    {
        $this->Date = $Date;
    }

    public function hasTopicAndUser(Topic $Topic, User $User): bool
    {
        $sameTopic = $this->getTopic()->getId() === $Topic->getId();

        return $sameTopic && $this->getUser()->getId() === $User->getId();
    }
}
