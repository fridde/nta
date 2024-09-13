<?php

namespace App\Entity;

use App\Repository\BoxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoxRepository::class), ORM\Table(name: "boxes")]
class Box 
{

}