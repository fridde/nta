<?php

namespace App\Entity;

use App\Repository\CourseRegistrationRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: CourseRegistrationRepository::class)]
class CourseRegistration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $User;

    #[ORM\ManyToOne(targetEntity: Topic::class)]
    private Topic $Topic;

    #[ORM\Column]
    private \DateTime $RegisteredAt;

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

    public function getRegisteredAt(): \DateTime
    {
        return $this->RegisteredAt;
    }

    public function setRegisteredAt(\DateTime $RegisteredAt): void
    {
        $this->RegisteredAt = $RegisteredAt;
    }

    #[ORM\PrePersist]
    public function setCreationDate(): void
    {
        $this->setRegisteredAt(Carbon::now());

    }








}
