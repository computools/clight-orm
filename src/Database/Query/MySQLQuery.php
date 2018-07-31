<?php

namespace Computools\CLightORM\Database\Query;

class MySQLQuery extends Query
{
	public function getQuery(): string
	{
		$where = empty($this->where) ? '' : ' WHERE ' . implode(' AND ', $this->where);
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

		$query = "
			SELECT
			{$this->select}
			FROM
			{$this->from} AS {$this->tables[$this->from]}
			{$joinString}
			{$where}
			{$group}
			{$order}
			{$limit}
		;";

		return $query;
	}
}