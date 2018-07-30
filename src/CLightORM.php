<?php

namespace Computools\CLightORM;

use Computools\CLightORM\Cache\CacheInterface;
use Computools\CLightORM\Database\Query\MySQLQuery;
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
	 * @var Database
	 */
	private $database;

	public function __construct(\PDO $pdo, ?CacheInterface $cache = null)
	{
		$this->database = new Database($pdo);
//		$this->database->setIdentifierDelimiter(null);

		$this->cache = $cache;
	}

	public function create(string $repositoryClass): RepositoryInterface
	{
		return new $repositoryClass($this->database, $this->cache);
	}
}