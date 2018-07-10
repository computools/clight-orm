<?php

namespace Computools\CLightORM\Mapper\Types;

abstract class ColumnType
{
	protected $columnName;

	public function __construct(?string $columnName = null)
	{
		$this->columnName = $columnName;
	}

	public function getColumnName(): ?string
	{
		return $this->columnName;
	}
}
