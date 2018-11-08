<?php

namespace Computools\CLightORM\Mapper;

use Computools\CLightORM\Entity\EntityInterface;

interface MapperInterface
{
	public function getTable(): string;

	public function getFields(): array;

	public function arrayToEntity(EntityInterface $entity, array $data): ?EntityInterface;

	public function entityToArray(EntityInterface $entity): array;

	public function cleanField(EntityInterface $entity, string $field): EntityInterface;

	public function getIdentifier(): string;

	public function getIdentifierEntityField(): string;
}