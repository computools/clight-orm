<?php

namespace Computools\CLightORM\Repository;

use Computools\CLightORM\Cache\CacheInterface;
use LessQL\Database;

class EntityRepositoryFactory
{
	/**
	 * @var Database
	 */
	private $database;

	/**
	 * @var CacheInterface
	 */
	private $cache;

	public function __construct(Database $database, ?CacheInterface $cache = null)
	{
		$this->database = $database;
		$this->database->setIdentifierDelimiter(null);
		$this->cache = $cache;
	}

	public function create(string $repositoryClass): RepositoryInterface
	{
		return new $repositoryClass($this->database, $this->cache);
	}
}