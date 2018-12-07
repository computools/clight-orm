<?php

namespace Computools\CLightORM\Entity;

use Computools\CLightORM\Mapper\Relations\RelationChangesList;

interface EntityInterface
{
	public function getIdValue(): ?int;

	public function isNew(): bool;

	public function getRelationChangesList(): RelationChangesList;

	public function fill(array $values): EntityInterface;

	public function addRelation(EntityInterface $entity): EntityInterface;

	public function removeRelation(EntityInterface $entity): EntityInterface;

	public function getOriginalData(): array;

	public function setOriginalData(array $originalData): EntityInterface;

    public function destroyToOneRelation(string $field): EntityInterface;

    public function getTable(): string;

    public function getIdentifier(): string;

    public function getIdentifierEntityField(): string;

    public function getIdentifierFromArray(array $data): ?int;

    public function getFields(): array;

    public function getOptionalFields(): array;
}