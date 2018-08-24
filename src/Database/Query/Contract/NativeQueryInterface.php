<?php

namespace Computools\CLightORM\Database\Query\Contract;

interface NativeQueryInterface extends ResultQueryInterface
{
	public function setQuery(string $query): NativeQueryInterface;

	public function setParams(array $params): NativeQueryInterface;

	public function addParameter(string $key, string $param): NativeQueryInterface;

	public function execute(array $params = [],  bool $mustReturnResult = true);
}
