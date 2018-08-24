<?php

namespace Computools\CLightORM\Database\Query\Contract;

use Computools\CLightORM\Database\Query\Structure\Join;

interface SelectQueryInterface extends ResultQueryInterface
{
	public function join(Join $join): SelectQueryInterface;

	public function limit(int $limit, int $offset = 0): SelectQueryInterface;

	public function whereArray(array $criteria): SelectQueryInterface;

	public function where(string $field, string $value): SelectQueryInterface;

	public function whereExpr(string $whereExpr): SelectQueryInterface;

	public function orderBy(string $field, string $direction = 'ASC'): SelectQueryInterface;

	public function select(string $select): SelectQueryInterface;

	public function from(string $table, string $alias = null): SelectQueryInterface;

	public function groupBy(string $field): SelectQueryInterface;
}