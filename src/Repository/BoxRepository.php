<?php

namespace App\Repository;

use App\Entity\Box;
use App\Utils\Coll;
use Doctrine\ORM\EntityRepository;

class BoxRepository extends EntityRepository
{

    public function getBoxSetCount(): array
    {
        $boxSets = Coll::create($this->findAll())
            ->map(fn(Box $box) => $box->Topic . '.' . $box->id)
            ->unique()
            ->map(fn(string $box) => explode('.', $box)[0])
            ->toArray();

        return array_count_values($boxSets);
    }

    public static function getNumberOfBoxSetsFromBoxes(Coll $boxes): int
    {
        return $boxes
            ->map(fn(Box $b) => $b->getBoxNumber())
            ->unique()
            ->count();
    }

}