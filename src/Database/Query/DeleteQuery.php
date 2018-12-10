<?php

namespace Computools\CLightORM\Database\Query;

use Computools\CLightORM\Database\Query\Contract\DeleteQueryInterface;
use Computools\CLightORM\Exception\QueryException;

abstract class DeleteQuery extends AbstractQuery implements DeleteQueryInterface
{
	protected $from;

	protected $where = [];

	protected $params = [];

	protected $whereExpr = [];

	public function from(string $from): DeleteQueryInterface
	{
		$this->from = $from;
		return $this;
	}

	public function where(string $field, $value): DeleteQueryInterface
	{
		$paramName = $this->generateParamName($field);
		$this->where[$field] = ':' . $paramName;
		$this->params[$paramName] = $value;

		return $this;
	}

    public function whereArray(array $criteria): DeleteQueryInterface
    {
        foreach ($criteria as $key => $value) {
            $paramName = $this->generateParamName($key);
            $this->where[$key] = ':' . $paramName;
            if (is_bool($value)) {
                $value = $value ? 'TRUE' : 'FALSE';
            }
            $this->params[$paramName] = $value;
        }
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
			throw new QueryException($statement->errorInfo());
		}
		return $this;
	}
}