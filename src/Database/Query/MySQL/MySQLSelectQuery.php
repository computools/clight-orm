<?php

namespace Computools\CLightORM\Database\Query\MySQL;

use Computools\CLightORM\Database\Query\SelectQuery;
use Computools\CLightORM\Exception\Query\TableNotSpecifiedException;

class MySQLSelectQuery extends SelectQuery
{
	public function getQuery(): string
	{
		if (empty($this->from)) {
			throw new TableNotSpecifiedException();
		}
		$where = [];
		foreach ($this->where as $key => $value) {
			$where[] = $key . '=' . $value;
		}
		$where = empty($this->where) && empty($this->whereExpr) ? '' : ' WHERE ' . implode(' AND ', array_merge($where, $this->whereExpr));
		$group = empty($this->group) ? '' : ' GROUP BY ' . implode(',', $this->group);
		$order = empty($this->order) ? '' : ' ORDER BY ' . implode(',', $this->order);
		$limit = empty($this->limit) ? '' : " LIMIT {$this->offset}, {$this->limit}";

		$joinStrings = [];

		foreach ($this->joins as $join) {
			$joinStrings[] = sprintf(
				'%s JOIN %s AS %s %s',
				$join->getType(),
				$join->getTable(),
				$join->getAlias(),
				$join->getCondition()
			);
		}

		$joinString = implode(' ', $joinStrings);

		$query = "SELECT {$this->select} FROM {$this->from} AS {$this->tables[$this->from]} {$joinString} {$where} {$group} {$order} {$limit};";

		return $query;
	}

	public function getConcatQuery(string $field, string $separator, string $alias): string
	{
		return "GROUP_CONCAT(DISTINCT $field SEPARATOR '$separator') AS $alias";
	}
}