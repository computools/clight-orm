<?php

namespace Computools\CLightORM\Database;

use Computools\CLightORM\Database\Query\MySQLQuery;
use Computools\CLightORM\Database\Query\Query;
use Computools\CLightORM\Database\Query\QueryInterface;

class Database
{
	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function createQuery(): Query
	{
		return new MySQLQuery();
	}

	public function executeQuery(Query &$query, array $params = [])
	{
		$statement = $this->pdo->prepare($query->getQuery());
		$statement->execute($params);
		$result = $statement->fetchAll();
		$query->setResult($result);
	}

	public function fetchAll()
	{

	}
}