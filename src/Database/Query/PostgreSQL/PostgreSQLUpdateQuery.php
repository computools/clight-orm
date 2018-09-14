<?php

namespace Computools\CLightORM\Database\Query\PostgreSQL;

use Computools\CLightORM\Database\Query\UpdateQuery;

class PostgreSQLUpdateQuery extends UpdateQuery
{
	public function getQuery(): string
	{
		$values = [];
		foreach ($this->values as $key => $value) {
			$values[] = $key . ' = ' . $value;
		}

		$values = implode(', ', $values);

		$where = [];
		foreach ($this->where as $key => $value) {
			$where[] = $key . '=' . $value;
		}
		$where = !empty($where) || !empty($this->whereExpr) ? 'WHERE ' . implode(' AND ', array_merge($this->whereExpr, $where)) : '';
		return sprintf("UPDATE %s SET %s %s;", $this->table, $values, $where);
	}
}