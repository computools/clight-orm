<?php

namespace Computools\CLightORM\Entity;

use Computools\CLightORM\Mapper\MapperInterface;

interface EntityInterface
{
	public function getId(): ?int;

	public function setId(?int $id): void;

	public function getMapper(): MapperInterface;

	public function isNew(): bool;

	public function getRelationChanges(): array;

	public function addRelation(EntityInterface $entity): void;

	public function removeRelation(EntityInterface $entity): void;
}