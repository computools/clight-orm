<?php

namespace Computools\CLightORM\Entity;

use Computools\CLightORM\Mapper\MapperInterface;

interface EntityInterface
{
	public function getIdValue(): ?int;

	public function getField(string $field);

	public function setField(string $field, $value = null);

	public function getMapper(): MapperInterface;

	public function isNew(): bool;

	public function getRelationChanges(): array;

	public function addRelation(EntityInterface $entity): void;

	public function removeRelation(EntityInterface $entity): void;
}