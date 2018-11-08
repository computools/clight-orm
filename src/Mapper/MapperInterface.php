<?php

namespace Computools\CLightORM\Mapper;

use Computools\CLightORM\Entity\EntityInterface;
use Computools\CLightORM\Mapper\Types\ColumnType;

interface MapperInterface
{
	public function getTable(): string;

    /**
     * @return ColumnType[]
     */
	public function getFields(): array;

	public function arrayToEntity(EntityInterface $entity, array $data): ?EntityInterface;

	public function entityToArray(EntityInterface $entity): array;

	public function getIdentifier(): string;

	public function getIdentifierEntityField(): string;
}