<?php

namespace App\Repository;

//use App\Entity\User;

use App\Entity\School;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    use Filterable;

    public function hasSchool(School $school): self
    {
        return $this->addAndFilter('School', $school);
    }
    
}