<?php

namespace Computools\CLightORM\Database\Query\Contract;

interface ResultQueryInterface extends CommonQueryInterface
{
	public function getResult(): ?array;

	public function getPick(string $field): array;

	public function getFirst(): ?array;
}