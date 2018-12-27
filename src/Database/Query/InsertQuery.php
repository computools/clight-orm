<?php

namespace Computools\CLightORM\Database\Query;

use Computools\CLightORM\Database\Query\Contract\InsertQueryInterface;
use Computools\CLightORM\Exception\QueryException;

abstract class InsertQuery extends AbstractQuery implements InsertQueryInterface
{
	protected $table;

	protected $values = [];

	protected $params = [];

	public function into(string $table): InsertQueryInterface
	{
		$this->table = $table;
		return $this;
	}

	public function values(array $values): InsertQueryInterface
	{
        foreach ($values as $key => $value) {
            $paramName = self::generateParamName($key);
            $this->values[$key] = ':' . $paramName;
            if (is_bool($value)) {
                $value = $value ? 'TRUE' : 'FALSE';
            }
            $this->params[$paramName] = $value;
        }
        return $this;
	}

	public function execute(array $params = []): InsertQueryInterface
	{
		$statement = $this->pdo->prepare($this->getQuery());
		$statement->execute(array_merge($this->params, $params));
		if ($statement->errorCode() !== \PDO::ERR_NONE) {
			throw new QueryException($statement->errorInfo());
		}
		return $this;
	}

	public function getLastId(): int
	{
		return $this->pdo->lastInsertId();
	}
}