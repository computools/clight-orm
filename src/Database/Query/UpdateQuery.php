<?php

namespace Computools\CLightORM\Database\Query;

abstract class UpdateQuery
{
	protected $table;

	protected $values;

	protected $pdo;

	protected $where = [];

	protected $whereExpr = [];

	protected $params = [];

	abstract public function getQuery(): string;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
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

	public function table(string $table): self
	{
		$this->table = $table;
		return $this;
	}

	public function values(array $values): self
	{
		foreach ($values as $key => $value) {
			$paramName = md5(uniqid()) . '_' . $key;
			$this->values[$key] = ':' . $paramName;
			$this->params[$paramName] = $value;
		}
		return $this;
	}

	public function execute(array $params = []): void
	{
		$statement = $this->pdo->prepare($this->getQuery());
		$statement->execute(array_merge($this->params, $params));
		if ($statement->errorCode() !== \PDO::ERR_NONE) {
			$b = 1;
		}
	}
}