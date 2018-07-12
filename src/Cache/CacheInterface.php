<?php

namespace Computools\CLightORM\Cache;

interface CacheInterface
{
	public function add(string $key, $value, int $expires);

	public function get(string $key);

	public function upsert(string $key, $value, int $expires);

	public function remove(string $key);
}