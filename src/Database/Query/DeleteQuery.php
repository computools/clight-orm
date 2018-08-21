<?php

namespace Computools\CLightORM\Database\Query;

use Computools\CLightORM\Database\Query\Contract\DeleteQueryInterface;

abstract class DeleteQuery extends AbstractQuery implements DeleteQueryInterface
{
	protected $from;

	protected $where;

	protected $params;

	protected $whereExpr;

	public function from(string $from): DeleteQueryInterface
	{
		$this->from = $from;
		return $this;
	}

	public function where(string $field, string $value): DeleteQueryInterface
	{
		$paramName = md5(uniqid()) . '_' . $field;
		$this->where[$field] = ':' . $paramName;
		$this->params[$paramName] = $value;

		return $this;
	}

	public function whereExpr(string $whereExpr): DeleteQueryInterface
	{
		$this->whereExpr[] = '(' . $whereExpr . ')';
		return $this;
	}

	public function execute(array $params = []): DeleteQueryInterface
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