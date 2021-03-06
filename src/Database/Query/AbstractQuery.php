<?php

namespace Computools\CLightORM\Database\Query;

abstract class AbstractQuery
{
	/**
	 * @var \PDO
	 */
	protected $pdo;

	abstract public function getQuery(): string;

	abstract public function execute(array $params = []);

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public static function generateParamName(string $key): string
    {
        return  md5(uniqid()) . '_' . str_replace('.', '', $key);
    }
}