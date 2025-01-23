<?php

namespace App\Repository;

use App\Utils\ExtendedCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Order;

/**
 * @method matching(Criteria $getCriteria)
 */
trait Filterable
{
    private Criteria $criteria;

    public function getCriteria(): Criteria
    {
        $this->setCriteriaIfNotSet();
        return $this->criteria;
    }

    public function setCriteriaIfNotSet(): void
    {
        $this->criteria ??= Criteria::create();
    }

    public function resetCriteria(): void
    {
        $this->criteria = Criteria::create();
    }

    public function applyFilterFunctions(array $filter = []): Criteria
    {
        $this->setCriteriaIfNotSet();
        array_walk($filter, fn($val, $key) => $this->{$key}($val));

        return $this->criteria;
    }

    public function addAndFilter(string $fieldName, $value, string $operator = Comparison::EQ): self
    {
        $this->setCriteriaIfNotSet();
        $comp = $this->createComparison($fieldName, $operator, $value);
        $this->criteria->andWhere($comp);

        return $this;
    }

    public function createComparison(string $fieldName, string $operator, $value): Comparison
    {
        return new Comparison($fieldName, $operator, $value);
    }

    public function addOrder(string $fieldName, string $direction = Criteria::ASC): self
    {
        return $this->addMultipleOrders([$fieldName => $direction]);
    }

    public function addMultipleOrders(array $orders = []): self
    {
        $sortedOrders = [];
        foreach ($orders as $field => $direction) {
            if (in_array($direction, [Order::Ascending, Order::Descending], true)) {
                $sortedOrders[$field] = $direction;
            } else {
                $sortedOrders[$direction] = Order::Ascending; // default value
            }
        }

        $this->setCriteriaIfNotSet();
        $this->criteria->orderBy($sortedOrders);

        return $this;
    }

    public function limitBy(int $limit): self
    {
        $this->setCriteriaIfNotSet();
        $this->criteria->setMaxResults($limit);

        return $this;
    }

    public function getMatching(): ExtendedCollection
    {
        $results = $this->matching($this->getCriteria());
        $this->resetCriteria();

        return new ExtendedCollection($results->toArray());
    }

}