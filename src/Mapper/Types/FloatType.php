<?php

namespace Computools\CLightORM\Mapper\Types;

use Computools\CLightORM\Entity\EntityInterface;

class FloatType extends ColumnType
{
    public function serialize($value, EntityInterface $entity)
    {
        return $value ? floatval($value) : null;
    }

    public function unserialize($value, EntityInterface $entity)
    {
        return $value ? floatval($value) : null;
    }
}