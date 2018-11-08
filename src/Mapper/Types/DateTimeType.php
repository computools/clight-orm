<?php

namespace Computools\CLightORM\Mapper\Types;

use Computools\CLightORM\Entity\EntityInterface;

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

    public function unserialize($value, EntityInterface $entity)
    {
        if (!$value || $value instanceof \DateTime) {
            return $value;
        } else {
            return new \DateTime($value);
        }
    }

    public function serialize($value, EntityInterface $entity)
    {
        return ($value) ? $value->format($this->getFormat()) : null;
    }
}