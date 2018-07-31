<?php

namespace Computools\CLightORM;

use Computools\CLightORM\Cache\CacheInterface;
use Computools\CLightORM\Database\Query\MySQLQuery;
use Computools\CLightORM\Database\Query\Query;
use Computools\CLightORM\Database\Query\QueryInterface;
use Computools\CLightORM\Repository\RepositoryInterface;
use Computools\CLightORM\Database\Database;

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
}