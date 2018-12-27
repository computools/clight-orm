<?php

namespace Computools\CLightORM\Repository;

use Computools\CLightORM\Entity\EntityInterface;
use Computools\CLightORM\Tools\Order;
use Computools\CLightORM\Tools\Pagination;

interface RepositoryInterface
{
	public function getEntityClass(): string;

	public function find(int $id, $with = [], int $expiration = 0): ?EntityInterface;

	public function findFirst($with = []): ?EntityInterface;

	public function findLast($with = []): ?EntityInterface;

	public function findBy(array $criteria, ?Order $order = null, $with = [], ?Pagination $pagination = null, int $expiration = 0): array;

	public function findOneBy(array $criteria, ?Order $order = null, $with = [], int $expiration = 0): ?EntityInterface;

	public function save(EntityInterface &$model, $with = [], $relationExistsCheck = false): EntityInterface;

	public function remove(EntityInterface $model): void;

	public function saveBunch(array $list, $with = [], $relationExistsCheck = false): array;

	public function removeBunch(array $list): void;
}