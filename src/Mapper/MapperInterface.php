<?php

namespace Computools\CLightORM\Mapper;

use Computools\CLightORM\Entity\EntityInterface;

interface MapperInterface
{
	public function arrayToEntity(EntityInterface $entity, array $data): ?EntityInterface;

	public function entityToArray(EntityInterface $entity): array;
}