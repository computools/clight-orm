<?php

namespace Computools\CLightORM\Mapper\Types;

class BooleanType extends ColumnType
{
	private $asInt;

	public function __construct(?string $columnName = null, bool $asInt = true)
	{
		parent::__construct($columnName);
		$this->asInt = $asInt;
	}

	public function asInt(): bool
	{
		return $this->asInt;
	}
}