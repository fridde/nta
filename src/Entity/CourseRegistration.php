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
    public ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    public User $User;

    #[ORM\ManyToOne(targetEntity: Topic::class)]
    public Topic $Topic;

    #[ORM\Column]
    public \DateTime $RegisteredAt;

    #[ORM\PrePersist]
    public function setCreationDate(): void
    {
        $this->RegisteredAt = Carbon::now();
    }








}
