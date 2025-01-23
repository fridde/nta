<?php

namespace App\Repository;

use App\Entity\Box;
use App\Utils\ExtendedCollection;
use Doctrine\ORM\EntityRepository;

class BoxRepository extends EntityRepository
{

    public function getBoxSetCount(): array
    {
        $boxSets = ExtendedCollection::create($this->findAll())
            ->map(fn(Box $box) => $box->getTopic()->getId() . '.' . $box->getId())
            ->unique()
            ->map(fn(string $box) => explode('.', $box)[0])
            ->toArray();

        return array_count_values($boxSets);
    }

    public static function getNumberOfBoxSetsFromBoxes(ExtendedCollection $boxes): int
    {
        return $boxes
            ->map(fn(Box $b) => $b->getBoxNumber())
            ->unique()
            ->count();
    }

}