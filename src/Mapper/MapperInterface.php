<?php

namespace Computools\CLightORM\Mapper;

use Computools\CLightORM\Entity\EntityInterface;

interface MapperInterface
{
	public function getTable(): string;

	public function getFields(): array;

	public function arrayToEntity(EntityInterface $entity, array $data): ?EntityInterface;

	public function mapList(EntityInterface $entity, string $field, array $collection): EntityInterface;

	public function mapEntity(EntityInterface $parentEntity, string $field, EntityInterface $childEntity): EntityInterface;

	public function entityToArray(EntityInterface $entity): array;

	public function cleanField(EntityInterface $entity, string $field): EntityInterface;

	public function getIdentifier(): string;

	public function getIdentifierEntityField(): string;

	public function defineGetterName(string $field, bool $addKeyword = true): string;

	public function defineSetterName(string $field, bool $addKeyword = true): string;
}