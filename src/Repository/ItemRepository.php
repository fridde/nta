<?php

namespace App\Repository;

use App\Entity\Item;
use App\Utils\Coll;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\AST\ComparisonExpression;


class ItemRepository extends EntityRepository
{
    use Filterable;

    public function getItems(array $itemIds): Coll
    {
        $this->addAndFilter('id', $itemIds, Comparison::IN);

        return $this->getMatching();
    }

}
