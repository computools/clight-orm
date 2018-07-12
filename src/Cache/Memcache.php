<?php

namespace Computools\CLightORM\Cache;

class Memcache implements CacheInterface
{
	const DEFAULT_EXPIRE_TIME = 60;
	const DEFAULT_MEMCACHED_PORT = 11211;
	const DEFAIULT_MEMCACHED_HOST = 'localhost';

	private $memcached;

	public function __construct(string $hostname = self::DEFAIULT_MEMCACHED_HOST, int $port = self::DEFAULT_MEMCACHED_PORT)
	{
		$this->memcached = new \Memcached();
		$this->memcached->addServer($hostname, $port);
	}

	public function get(string $key)
	{
		return unserialize($this->memcached->get($key));
	}

	public function add(string $key, $value, int $expires = self::DEFAULT_EXPIRE_TIME): void
	{
		$this->memcached->add($key, serialize($value), $expires);
	}

	public function upsert(string $key, $value, int $expires = self::DEFAULT_EXPIRE_TIME): void
	{
		if (!$this->memcached->get($key)) {
			$this->memcached->add($key, serialize($value), $expires);
		} else {
			$this->memcached->replace($key, serialize($value), $expires);
		}
	}

	public function remove(string $key): void
	{
		$this->memcached->delete($key);
	}
}