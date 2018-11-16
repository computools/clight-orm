<?php

namespace Computools\CLightORM\Mapper\Types;

use Computools\CLightORM\Entity\EntityInterface;

class JsonType extends ColumnType
{
    public function serialize($value, EntityInterface $entity)
    {
        return json_encode($value);
    }

    public function unserialize($value, EntityInterface $entity)
    {
        return json_decode($value, true);
    }
}