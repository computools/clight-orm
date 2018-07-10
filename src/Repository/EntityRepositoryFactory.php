<?php

namespace Computools\CLightORM\Repository;

use LessQL\Database;

class EntityRepositoryFactory
{
	/**
	 * @var Database
	 */
	private $database;

	public function __construct(Database $database)
	{
		$this->database = $database;
		$this->database->setIdentifierDelimiter(null);
	}

	public function create(string $repositoryClass): RepositoryInterface
	{
		return new $repositoryClass($this->database);
	}
}