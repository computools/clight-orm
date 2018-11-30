<?php

namespace Computools\CLightORM\Entity;

use Computools\CLightORM\Mapper\MapperInterface;
use Computools\CLightORM\Mapper\Relations\RelationChangesList;

interface EntityInterface
{
	public function getIdValue(): ?int;

	public function getMapper(): MapperInterface;

	public function isNew(): bool;

	public function getRelationChangesList(): RelationChangesList;

	public function fill(array $values): EntityInterface;

	public function addRelation(EntityInterface $entity): EntityInterface;

	public function removeRelation(EntityInterface $entity): EntityInterface;

	public function getOriginalData(): array;

	public function setOriginalData(array $originalData): EntityInterface;

    public function destroyToOneRelation(string $field): EntityInterface;

    public static function getMappedFieldNames(string $key);
}