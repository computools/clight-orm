<?php

namespace Computools\CLightORM\Database\Query;

use Computools\CLightORM\Database\Query\Contract\NativeQueryInterface;
use Computools\CLightORM\Exception\Query\QueryIsEmptyException;
use Computools\CLightORM\Exception\QueryException;

class NativeQuery extends AbstractQuery implements NativeQueryInterface
{
	private $query = null;

	private $params = [];

	private $result = null;

	public function setQuery(string $query): NativeQueryInterface
	{
		$this->query = $query;
		return $this;
	}

	public function setParams(array $params): NativeQueryInterface
	{
		$this->params = $params;
		return $this;
	}

	public function addParameter(string $key, string $param): NativeQueryInterface
	{
		$this->params[$key] = $param;
		return $this;
	}

	public function getQuery(): string
	{
		return $this->query;
	}

	public function execute(array $params = [], bool $mustReturnResult = true): NativeQueryInterface
	{
		if (empty($this->query)) {
			throw new QueryIsEmptyException();
		}

		$statement = $this->pdo->prepare($this->query);
		$statement->execute(array_merge($this->params, $params));

		if ($statement->errorCode() !== \PDO::ERR_NONE) {
			throw new QueryException($statement->errorInfo());
		}

		if ($mustReturnResult) {
			$result = $statement->fetchAll();
			$this->result = $result;
		}
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
}