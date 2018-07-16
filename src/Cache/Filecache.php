<?php

namespace Computools\CLightORM\Cache;

class Filecache implements CacheInterface
{
	const DEFAULT_EXPIRE_TIME = 60;

	const DEFAULT_CACHE_DIR = 'cache';

	private $cacheDir;

	public function __construct(string $cacheDir = self::DEFAULT_CACHE_DIR)
	{
		$this->cacheDir = $cacheDir . '/';
		if (!is_dir($this->cacheDir)) {
			mkdir($this->cacheDir);
		}
	}

	public function get(string $key)
	{
		if (!file_exists($this->cacheDir . $key)) {
			return null;
		}
		$data = unserialize(file_get_contents($this->cacheDir . $key));
		if (time() > $data['expires']) {
			$this->remove($key);
			return null;
		}
		return $data['data'];
	}

	public function remove(string $key): void
	{
		if (file_exists($this->cacheDir . $key)) {
			unlink($this->cacheDir . $key);
		}
	}

	public function add(string $key, $value, int $expires): void
	{
		$data = [
			'data' => $value,
			'expires' => time() + $expires
		];
		file_put_contents($this->cacheDir . $key, serialize($data));
	}

	public function upsert(string $key, $value, int $expires): void
	{
		$data = [
			'data' => $value,
			'expires' => time() + $expires
		];
		file_put_contents($this->cacheDir . $key, serialize($data));
	}
}