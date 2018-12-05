<?php

namespace Computools\CLightORM\Helper;

use Computools\CLightORM\Entity\EntityInterface;
use Computools\CLightORM\Mapper\FieldMapStorage;

class ReflectionHelper
{
    public static function setEntityProperty(EntityInterface &$entity, string $key, $value): void
    {
        FieldMapStorage::getReflectionProperty($entity, $key)->setValue($entity, $value);
    }

    public static function getEntityProperty(EntityInterface $entity, string $key)
    {
        return FieldMapStorage::getReflectionProperty($entity, $key)->getValue($entity);
    }
}