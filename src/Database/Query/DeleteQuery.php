<?php

namespace Computools\CLightORM\Database\Query;

abstract class DeleteQuery
{
	abstract public function getQuery(): string;

	private $pdo;

	protected $from;

	protected $where;

	protected $params;

	protected $whereExpr;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function from(string $from): self
	{
		$this->from = $from;
		return $this;
	}

	public function where(string $field, string $value): self
	{
		$paramName = md5(uniqid()) . '_' . $field;
		$this->where[$field] = ':' . $paramName;
		$this->params[$paramName] = $value;

		return $this;
	}

	public function whereExpr(string $whereExpr): self
	{
		$this->whereExpr[] = '(' . $whereExpr . ')';
		return $this;
	}

	public function execute(array $params = []): self
	{
		$statement = $this->pdo->prepare($this->getQuery());
		$statement->execute(array_merge($this->params, $params));
		if ($statement->errorCode() !== \PDO::ERR_NONE) {
			print_r($statement->errorInfo());
			die();
		}
		return $this;
	}
}