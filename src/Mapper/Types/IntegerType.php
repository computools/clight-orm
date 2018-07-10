<?php

namespace Computools\CLightORM\Mapper\Types;

class IntegerType extends ColumnType
{
	public function __construct(?string $columnName = null)
	{
		parent::__construct($columnName);
	}
}