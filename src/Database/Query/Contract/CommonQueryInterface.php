<?php

namespace Computools\CLightORM\Database\Query\Contract;

interface CommonQueryInterface
{
	public function getQuery(): string;

	public function execute();
}