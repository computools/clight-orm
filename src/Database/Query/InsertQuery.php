<?php

namespace Computools\CLightORM\Database\Query;

abstract class InsertQuery
{
	protected $table;

	protected $values = [];

	protected $params = [];

	private $pdo;

	abstract public function getQuery(): string;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function into(string $table): self
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

	public function execute(array $params = []): int
	{
		$statement = $this->pdo->prepare($this->getQuery());
		$statement->execute(array_merge($this->params, $params));
		if ($statement->errorCode() !== \PDO::ERR_NONE) {
			print_r($statement->errorInfo());
			die();
		}
		return $this->pdo->lastInsertId();
	}
}