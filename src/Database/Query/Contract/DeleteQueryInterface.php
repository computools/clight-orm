<?php

namespace Computools\CLightORM\Database\Query\Contract;

interface DeleteQueryInterface extends CommonQueryInterface
{
	public function from(string $from): DeleteQueryInterface;

	public function where(string $field, string $value): DeleteQueryInterface;

	public function whereExpr(string $whereExpr): DeleteQueryInterface;
}
