<?php

namespace Computools\CLightORM\Mapper\Types;

class DateTimeType extends ColumnType
{
	const DEFAULT_FORMAT = 'Y-m-d H:i:s';

	private $format;

	public function __construct(?string $columnName = null, string $format = self::DEFAULT_FORMAT)
	{
		parent::__construct($columnName);
		$this->format = $format;
	}

	public function getFormat(): string
	{
		return $this->format;
	}
}