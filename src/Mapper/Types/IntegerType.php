<?php

namespace Computools\CLightORM\Mapper\Types;

use Computools\CLightORM\Entity\EntityInterface;

class IntegerType extends ColumnType
{
	public function serialize($value, EntityInterface $entity)
    {
        return intval($value);
    }

    public function unserialize($value, EntityInterface $entity)
    {
        return intval($value);
    }
}