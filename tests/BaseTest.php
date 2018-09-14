<?php

namespace Computools\CLightORM\Test;

use Computools\CLightORM\Cache\Filecache;
use Computools\CLightORM\Cache\Memcache;
use Computools\CLightORM\CLightORM;


class BaseTest extends \PHPUnit_Framework_TestCase
{
	public static $dumpLoaded = false;

	/**
	 * @var CLightORM
	 */
	protected $cligtORM;

	public function setUp()
	{
		// mysql
//		$pdo = new \PDO(
//			sprintf(
//				'%s:host=%s;port=%s;dbname=%s',
//				'mysql',
//				'127.0.0.1',
//				'3306',
//				'test'
//			),
//		'root',
//		'1234'
//		);

		// pgsql
		$pdo = new \PDO(
			sprintf(
				'%s:host=%s;port=%s;dbname=%s',
				'pgsql',
				'127.0.0.1',
				'5432',
				'test'
			),
			'root',
			'1234'
		);


		$this->cligtORM = new CLightORM($pdo, new Filecache());
	}

	public function testInitDB()
	{
		if (!static::$dumpLoaded) {
			//$this->database->query(file_get_contents(__DIR__ . '/mysql_db.sql'));
			self::$dumpLoaded = true;
		}
	}
}