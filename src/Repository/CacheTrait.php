<?php

namespace Computools\CLightORM\Repository;

trait CacheTrait
{
	protected function generateCacheKey($criteria)
	{
		return md5($this->table . serialize($criteria));
	}

	protected function getFromCache($criteria, $expiration)
	{
		if (!$expiration || !$this->cache) {
			return false;
		}
		return $this->cache->get($this->generateCacheKey($criteria));
	}

	protected function putToCache($value, $criteria, $expiration)
	{
		if (!$expiration || !$this->cache) {
			return false;
		}
		return $this->cache->upsert($this->generateCacheKey($criteria), $value, $expiration);
	}
}