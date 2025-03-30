<?php

namespace App\Repository;

use App\Entity\Topic;
use App\Enums\InventoryType;
use App\Utils\Coll;
use Doctrine\ORM\EntityRepository;


class InventoryRepository extends EntityRepository
{
    use Filterable;

    public function getInventoryForTopic(Topic $topic): Coll
    {
        $this->addAndFilter('InventoryType', InventoryType::BOX);
        $this->addAndFilter('Topic', $topic);
        $this->addOrder('ListRank');

        return $this->getMatching();
    }

}
