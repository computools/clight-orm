<?php

namespace Computools\CLightORM\Database\Query;

use Computools\CLightORM\Database\Query\Contract\SelectQueryInterface;
use Computools\CLightORM\Database\Query\Structure\Join;
use Computools\CLightORM\Exception\Query\InvalidOrderDirectionException;
use Computools\CLightORM\Exception\QueryException;

abstract class SelectQuery extends AbstractQuery implements SelectQueryInterface
{
	protected $possibleOrderDirections = [
		'asc',
		'desc'
	];

	protected $select = '*';

	/**
	 * @var Join[]
	 */
	protected $joins = [];

	protected $from;

	protected $limit;

	protected $offset = 0;

	protected $order = [];

	protected $group = [];

	protected $where = [];

	protected $whereExpr = [];

	protected $tables = [];

	protected $subqueries = [];

	protected $result = null;

	protected $params = [];

	public function join(Join $join): SelectQueryInterface
	{
		$this->joins[] = $join;
		$alias = $join->getAlias() ? $join->getAlias() : $join->getTable();
		$this->tables[$join->getTable()] = $alias;
		return $this;
	}

	public function limit(int $limit, int $offset = 0): SelectQueryInterface
	{
		$this->limit = $limit;
		$this->offset = $offset;
		return $this;
	}

	public function whereArray(array $criteria): SelectQueryInterface
	{
		foreach ($criteria as $key => $value) {
			$paramName = md5(uniqid()) . '_' . $key;
			$this->where[$key] = ':' . $paramName;
			$this->params[$paramName] = $value;
		}
		return $this;
	}

	public function where(string $field, string $value): SelectQueryInterface
	{
		$paramName = md5(uniqid()) . '_' . $field;
		$this->where[$field] = ':' . $paramName;
		$this->params[$paramName] = $value;

		return $this;
	}

	public function whereExpr(string $whereExpr): SelectQueryInterface
	{
		$this->whereExpr[] = '(' . $whereExpr . ')';
		return $this;
	}

	public function orderBy(string $field, string $direction = 'ASC'): SelectQueryInterface
	{
		if (!in_array(mb_strtolower($direction), $this->possibleOrderDirections)) {
			throw new InvalidOrderDirectionException();
		}
		$this->order[] = "{$field} {$direction}";
		return $this;
	}

	public function select(string $select): SelectQueryInterface
	{
		$this->select = $select;
		return $this;
	}

	public function from(string $table, string $alias = null): SelectQueryInterface
	{
		$this->from = $table;
		if (!$alias) {
			$alias = $table;
		}
		$this->tables[$table] = $alias;
		return $this;
	}

	public function groupBy(string $field): SelectQueryInterface
	{
		$this->group[] = $field;
		return $this;
	}

	public function getResult(): ?array
	{
		return $this->result;
	}

	public function getFirst(): ?array
	{
		return $this->result[0] ?? null;
	}

	public function setResult($result): SelectQueryInterface
	{
		$this->result = $result;
		return $this;
	}

	public function execute(array $params = []): SelectQueryInterface
	{
		$statement = $this->pdo->prepare($this->getQuery());
		$statement->execute(array_merge($this->params, $params));

		if ($statement->errorCode() !== \PDO::ERR_NONE) {
			throw new QueryException($statement->errorInfo());
		}
		$result = $statement->fetchAll();
		$this->setResult($result);
		return $this;
	}
}