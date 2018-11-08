<?php

namespace Computools\CLightORM\Mapper\Types;

use Computools\CLightORM\Entity\EntityInterface;

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

	public function serialize($value, EntityInterface $entity)
    {
        return $this->asInt() ? ($value ? 1 : 0) : ($value);
    }

    public function unserialize($value, EntityInterface $entity)
    {
        return (int) $value ? true : false;
    }
}