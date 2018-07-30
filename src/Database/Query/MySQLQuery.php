<?php

namespace Computools\CLightORM\Database\Query;

class MySQLQuery extends Query
{
	public function getQuery()
	{
		$where = empty($this->where) ? '' : ' WHERE ' . implode(' AND ', $this->where);
		$group = empty($this->group) ? '' : ' GROUP BY ' . implode(',', $this->group);
		$order = empty($this->order) ? '' : ' ORDER BY ' . implode(',', $this->order);
		$limit = empty($this->limit) ? '' : " LIMIT {$this->offset}, {$this->limit}";

		$query = "
			SELECT
			$this->select
			FROM
			{$this->from} AS {$this->tables[$this->from]}
			{$where}
			$group
			$order
			$limit
		;";

		return $query;
	}
}