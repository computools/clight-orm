<?php

namespace Computools\CLightORM\Database\Query\Contract;

interface InsertQueryInterface extends CommonQueryInterface
{
	public function into(string $table): InsertQueryInterface;

	public function values(array $values): InsertQueryInterface;

	public function getLastId(): int;
}