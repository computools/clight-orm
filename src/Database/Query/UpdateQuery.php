<?php

namespace Computools\CLightORM\Database\Query;

use Computools\CLightORM\Database\Query\Contract\UpdateQueryInterface;

abstract class UpdateQuery extends AbstractQuery implements UpdateQueryInterface
{
	protected $table;

	protected $values;

	protected $where = [];

	protected $whereExpr = [];

	protected $params = [];

	public function where(string $field, string $value): UpdateQueryInterface
	{
		$paramName = md5(uniqid()) . '_' . $field;
		$this->where[$field] = ':' . $paramName;
		$this->params[$paramName] = $value;

		return $this;
	}

	public function whereExpr(string $whereExpr): UpdateQueryInterface
	{
		$this->whereExpr[] = '(' . $whereExpr . ')';
		return $this;
	}

	public function table(string $table): UpdateQueryInterface
	{
		$this->table = $table;
		return $this;
	}

	public function values(array $values): UpdateQueryInterface
	{
		foreach ($values as $key => $value) {
			$paramName = md5(uniqid()) . '_' . $key;
			$this->values[$key] = ':' . $paramName;
			$this->params[$paramName] = $value;
		}
		return $this;
	}

	public function execute(array $params = []): UpdateQueryInterface
	{
		$statement = $this->pdo->prepare($this->getQuery());
		$statement->execute(array_merge($this->params, $params));
		if ($statement->errorCode() !== \PDO::ERR_NONE) {
			$b = 1;
		}
		return $this;
	}
}