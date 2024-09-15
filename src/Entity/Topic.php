<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: "topics")]
class Topic 
{
    #[ORM\Id, ORM\Column]
    protected string $id;

    #[ORM\Column(unique: true)]
    protected string $Name;

    

}