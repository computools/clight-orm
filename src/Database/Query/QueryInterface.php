<?php

namespace Computools\CLightORM\Database\Query;

interface QueryInterface
{
	public function getQuery();

	public function select(string $select);

	public function from(string $from);

	public function join(string $join);

	public function orderBy(string $field, string $direction);

	public function where(string $where);
}