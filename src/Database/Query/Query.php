<?php

namespace Computools\CLightORM\Database\Query;

abstract class
Query
{
	protected $pdo;

	abstract public function getQuery(): string;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function execute(array $params = []): self
	{
		$statement = $this->pdo->prepare($this->getQuery());
		$statement->execute($params);

//		if ($statement->errorCode()) {
//			print_r($statement->errorInfo());
//			die();
//		}
		$result = $statement->fetchAll();
		$this->setResult($result);
		return $this;
	}

	protected $select;

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

	protected $tables = [];

	protected $subqueries = [];

	protected $result = null;

	public function addSubquery(Query $query)
	{
		$this->subqueries[] = $query;
		return $this;
	}

	public function join(Join $join): self
	{
		$this->joins[] = $join;
		$alias = $join->getAlias() ? $join->getAlias() : $join->getTable();
		$this->tables[$join->getTable()] = $alias;
		return $this;
	}

	public function limit(int $limit, int $offset = 0)
	{
		$this->limit = $limit;
		$this->offset = $offset;
		return $this;
	}

	public function where(string $where): self
	{
		$this->where[] = $where;
		return $this;
	}

	public function orderBy(string $field, string $direction = 'ASC'): self
	{
		$this->order[] = "{$field} {$direction}";
		return $this;
	}

	public function select(string $select): self
	{
		$this->select = $select;
		return $this;
	}

	public function from(string $table, string $alias = null)
	{
		$this->from = $table;
		if (!$alias) {
			$alias = $table;
		}
		$this->tables[$table] = $alias;
		return $this;
	}

	public function groupBy(string $field)
	{
		$this->group[] = $field;
		return $this;
	}

	public function getResult()
	{
		return $this->result;
	}

	public function getFirst()
	{
		return $this->result[0] ?? null;
	}


	public function setResult($result)
	{
		$this->result = $result;
		return $this;
	}
}