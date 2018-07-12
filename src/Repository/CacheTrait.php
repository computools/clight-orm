<?php

namespace Computools\CLightORM\Repository;

trait CacheTrait
{
	protected function generateCacheKey($criteria, $with)
	{
		return md5($this->table . serialize($criteria) . serialize($with));
	}

	protected function getFromCache($criteria, array $with, $expiration)
	{
		if (!$expiration || !$this->cache) {
			return false;
		}
		return $this->cache->get($this->generateCacheKey($criteria, $with));
	}

	protected function putToCache($value, $criteria, array $with, $expiration)
	{
		if (!$expiration || !$this->cache) {
			return false;
		}
		return $this->cache->upsert($this->generateCacheKey($criteria, $with), $value, $expiration);
	}
}