<?php

namespace Computools\CLightORM\Mapper\Types;

use Computools\CLightORM\Entity\EntityInterface;

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

	public function serialize($value, EntityInterface $entity)
    {
        return $value;
    }

	public function unserialize($value, EntityInterface $entity)
    {
        return $value;
    }
}
