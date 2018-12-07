<?php

namespace Computools\CLightORM\Mapper\Types;

use Computools\CLightORM\Entity\EntityInterface;

class FloatType extends ColumnType
{
    public function serialize($value, EntityInterface $entity)
    {
        return floatval($value);
    }

    public function unserialize($value, EntityInterface $entity)
    {
        return floatval($value);
    }
}