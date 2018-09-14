<?php

namespace Computools\CLightORM;

use Computools\CLightORM\Cache\CacheInterface;
use Computools\CLightORM\Database\Query\Contract\{
	DeleteQueryInterface,
	InsertQueryInterface,
	NativeQueryInterface,
	SelectQueryInterface,
	UpdateQueryInterface
};

use Computools\CLightORM\Database\Query\MySQL\MySQLDeleteQuery;
use Computools\CLightORM\Database\Query\MySQL\MySQLInsertQuery;
use Computools\CLightORM\Database\Query\MySQL\MySQLSelectQuery;
use Computools\CLightORM\Database\Query\MySQL\MySQLUpdateQuery;
use Computools\CLightORM\Database\Query\PostgreSQL\PostgreSQLSelectQuery;
use Computools\CLightORM\Database\Query\PostgreSQL\PostgreSQLUpdateQuery;
use Computools\CLightORM\Database\Query\PostgreSQL\PostgreSQLInsertQuery;
use Computools\CLightORM\Database\Query\PostgreSQL\PostgreSQLDeleteQuery;
use Computools\CLightORM\Database\Query\NativeQuery;

use Computools\CLightORM\Exception\DriverIsNotSupportedException;
use Computools\CLightORM\Repository\RepositoryInterface;

class CLightORM
{
	private const SELECT = 'select';
	private const UPDATE = 'update';
	private const INSERT = 'insert';
	private const DELETE = 'delete';

	private $classMap = [
		'mysql' => [
			self::SELECT => MySQLSelectQuery::class,
			self::UPDATE => MySQLUpdateQuery::class,
			self::INSERT => MySQLInsertQuery::class,
			self::DELETE => MySQLDeleteQuery::class
		],
		'pgsql' => [
			self::SELECT => PostgreSQLSelectQuery::class,
			self::UPDATE => PostgreSQLUpdateQuery::class,
			self::INSERT => PostgreSQLInsertQuery::class,
			self::DELETE => PostgreSQLDeleteQuery::class
		]
	];

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
		$class = $this->getQueryClass(self::SELECT);
		return new $class($this->pdo);
	}

	public function createInsertQuery(): InsertQueryInterface
	{
		$class = $this->getQueryClass(self::INSERT);
		return new $class($this->pdo);
	}

	public function createUpdateQuery(): UpdateQueryInterface
	{
		$class = $this->getQueryClass(self::UPDATE);
		return new $class($this->pdo);
	}

	public function createDeleteQuery(): DeleteQueryInterface
	{
		$class = $this->getQueryClass(self::DELETE);
		return new $class($this->pdo);
	}

	public function createNativeQuery(): NativeQueryInterface
	{
		return new NativeQuery($this->pdo);
	}

	private function getQueryClass(string $type)
	{
		$driver = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
		if (!$class = $this->classMap[$driver][$type] ?? null) {
			throw new DriverIsNotSupportedException($driver);
		}
		return $class;
	}
}