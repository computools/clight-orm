<?php

namespace Computools\CLightORM\Database\Query\Contract;

interface UpdateQueryInterface extends CommonQueryInterface
{
	public function table(string $table): UpdateQueryInterface;

	public function values(array $values): UpdateQueryInterface;

	public function where(string $field, $value): UpdateQueryInterface;

	public function whereExpr(string $whereExpr): UpdateQueryInterface;
}