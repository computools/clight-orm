<?php

namespace Computools\CLightORM;

use Computools\CLightORM\Cache\CacheInterface;
use Computools\CLightORM\Database\Query\DeleteQuery;
use Computools\CLightORM\Database\Query\InsertQuery;
use Computools\CLightORM\Database\Query\MySQL\MySQLDeleteQuery;
use Computools\CLightORM\Database\Query\MySQL\MySQLInsertQuery;
use Computools\CLightORM\Database\Query\MySQL\MySQLQuery;
use Computools\CLightORM\Database\Query\MySQL\MySQLUpdateQuery;
use Computools\CLightORM\Database\Query\Query;
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

	public function createQuery(): Query
	{
		return new MySQLQuery($this->pdo);
	}

	public function createInsertQuery(): InsertQuery
	{
		return new MySQLInsertQuery($this->pdo);
	}

	public function createUpdateQuery(): UpdateQuery
	{
		return new MySQLUpdateQuery($this->pdo);
	}

	public function createDeleteQuery(): DeleteQuery
	{
		return new MySQLDeleteQuery($this->pdo);
	}
}