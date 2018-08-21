<?php

namespace Computools\CLightORM\Database\Query\MySQL;

use Computools\CLightORM\Database\Query\Contract\DeleteQueryInterface;
use Computools\CLightORM\Database\Query\DeleteQuery;

class MySQLDeleteQuery extends DeleteQuery implements DeleteQueryInterface
{
	public function getQuery(): string
	{
		$where = [];
		foreach ($this->where as $key => $value) {
			$where[] = $key . '=' . $value;
		}
		$where = !empty($where) || !empty($this->whereExpr) ? 'WHERE ' . implode(' AND ', array_merge($this->whereExpr, $where)) : '';

		$query = sprintf('DELETE FROM %s %s', $this->from, $where);
		return $query;
	}
}