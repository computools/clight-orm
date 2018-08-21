<?php

namespace Computools\CLightORM\Repository;

use Computools\CLightORM\Entity\EntityInterface;
use Computools\CLightORM\Tools\Order;
use Computools\CLightORM\Tools\Pagination;

interface RepositoryInterface
{
	public function getEntityClass(): string;

	public function find(int $id, array $with = [], int $expiration = 0): ?EntityInterface;

	public function findFirst(array $with = []): ?EntityInterface;

	public function findLast(array $with = []): ?EntityInterface;

	public function findBy(array $criteria, ?Order $order = null, array $with = [], ?Pagination $pagination = null, int $expiration = 0): array;

	public function findOneBy(array $criteria, ?Order $order = null, array $with = [], int $expiration = 0): ?EntityInterface;

	public function save(EntityInterface &$model, array $with = [], $relationExistsCheck = false): EntityInterface;

	public function remove(EntityInterface $model): void;
}