<?php

namespace Computools\CLightORM\Database\Query\MySQL;

use Computools\CLightORM\Database\Query\InsertQuery;

class MySQLInsertQuery extends InsertQuery
{
	public function getQuery(): string
	{
		$query = sprintf(
			"INSERT INTO %s(%s) VALUES(%s);",
			$this->table,
			implode(', ', array_keys($this->values)),
			implode(', ', array_values($this->values))
		);

		return $query;
	}
}