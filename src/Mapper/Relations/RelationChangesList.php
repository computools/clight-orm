<?php

namespace Computools\CLightORM\Mapper\Relations;

class RelationChangesList
{
    public const RELATION_CHANGE_ADD = 'add';
    public const RELATION_CHANGE_REMOVE = 'remove';

    private $toMany = [];

    private $toOne = [];

    public function addToManyChange(string $relation, string $type, int $id): void
    {
        if (!isset($this->toMany[$relation])) {
            $this->toMany[$relation] = [];
        }
        if (!isset($this->toMany[$relation][$type])) {
            $this->toMany[$relation][$type] = [];
        }

        $this->toMany[$relation][$type][] = $id;
    }

    public function addToOneChange(string $entityField, string $tableField): void
    {
        $this->toOne[$entityField] = $tableField;
    }

    public function getToManyChanges(): array
    {
        return $this->toMany;
    }

    public function getToOneChanges(): array
    {
        return $this->toOne;
    }

    public function getToOneByKey(string $key): ?string
    {
        return isset($this->toOne[$key]) ? $this->toOne[$key] : null;
    }
}