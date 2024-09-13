<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class), ORM\Table(name: "users")]
class User implements UserInterface
{
    
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    protected int $id; 

    #[ORM\Column(nullable: true)]
    protected ?string $FirstName;

    #[ORM\Column(nullable: true)]
    protected ?string $LastName;

    #[ORM\Column(unique: true)]
    protected string $Mail;
}