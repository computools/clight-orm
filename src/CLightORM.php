<?php

namespace Computools\CLightORM;

use Computools\CLightORM\Cache\CacheInterface;
use Computools\CLightORM\Database\Query\Contract\DeleteQueryInterface;
use Computools\CLightORM\Database\Query\Contract\InsertQueryInterface;
use Computools\CLightORM\Database\Query\Contract\SelectQueryInterface;
use Computools\CLightORM\Database\Query\Contract\UpdateQueryInterface;
use Computools\CLightORM\Database\Query\DeleteQuery;
use Computools\CLightORM\Database\Query\InsertQuery;
use Computools\CLightORM\Database\Query\MySQL\MySQLDeleteQuery;
use Computools\CLightORM\Database\Query\MySQL\MySQLInsertQuery;
use Computools\CLightORM\Database\Query\MySQL\MySQLSelectSelectQuery;
use Computools\CLightORM\Database\Query\MySQL\MySQLUpdateQuery;
use Computools\CLightORM\Database\Query\SelectQuery;
use Computools\CLightORM\Database\Query\UpdateQuery;
use Computools\CLightORM\Repository\RepositoryInterface;

class CLightORM
{
	/**
	 * @var CacheInterface
	 */
	private $cache;

	/**
	 * @var \PDO
	 */
	private $pdo;

	public function __construct(\PDO $pdo, ?CacheInterface $cache = null)
	{
		$this->pdo = $pdo;
		$this->cache = $cache;
	}

	public function createRepository(string $repositoryClass): RepositoryInterface
	{
		return new $repositoryClass($this, $this->cache);
	}

	public function createQuery(): SelectQueryInterface
	{
		return new MySQLSelectSelectQuery($this->pdo);
	}

	public function createInsertQuery(): InsertQueryInterface
	{
		return new MySQLInsertQuery($this->pdo);
	}

	public function createUpdateQuery(): UpdateQueryInterface
	{
		return new MySQLUpdateQuery($this->pdo);
	}

	public function createDeleteQuery(): DeleteQueryInterface
	{
		return new MySQLDeleteQuery($this->pdo);
	}
}