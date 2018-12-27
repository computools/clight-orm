<?php

namespace Computools\CLightORM\Tools;

class RelatedData
{
    /**
     * @var Condition[]
     */
    private $conditions = [];

    /**
     * @var string
     */
    private $relation;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var RelatedDataSet
     */
    private $relatedDataSet;

    /**
     * @var Pagination
     */
    private $pagination;

    public function __construct(string $relation, ?Condition $condition = null, ?Order $order = null)
    {
        $this->relation = $relation;
        $this->condition = $condition;
        $this->order = $order;
    }

    public function setRelatedDataSet(RelatedDataSet $relatedDataSet): RelatedData
    {
        $this->relatedDataSet = $relatedDataSet;
        return $this;
    }

    public function setOrder(Order $order): RelatedData
    {
        $this->order = $order;
        return $this;
    }

    public function addCondition(Condition $condition): RelatedData
    {
        $this->conditions[] = $condition;
        return $this;
    }

    public function setPagination(Pagination $pagination): RelatedData
    {
        $this->pagination = $pagination;
        return $this;
    }

    /**
     * @return Condition[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function getRelation(): string
    {
        return $this->relation;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function getRelatedDataSet(): ?RelatedDataSet
    {
        return $this->relatedDataSet;
    }

    public function getPagination(): ?Pagination
    {
        return $this->pagination;
    }
}
