<?php

namespace Computools\CLightORM\Helper;

use Computools\CLightORM\Entity\EntityInterface;
use Computools\CLightORM\Exception\InvalidFieldMapException;

class ReflectionHelper
{
    public static function setEntityProperty(EntityInterface &$entity, string $key, $value): void
    {
        self::getPropertyReflection($entity, $key)->setValue($entity, $value);
    }

    public static function getEntityProperty(EntityInterface $entity, string $key)
    {
        return self::getPropertyReflection($entity, $key)->getValue($entity);
    }

    private static function getPropertyReflection(EntityInterface $entity, string $key): \ReflectionProperty
    {
        $reflection = new \ReflectionClass($entity);
        list($camelCase, $underScore) = StringHelper::getStringOptions($key);
        if ($reflection->hasProperty($camelCase)) {
            $property = $reflection->getProperty($camelCase);
        } else if ($reflection->hasProperty($underScore)) {
            $property = $reflection->getProperty($underScore);
        } else {
            throw new InvalidFieldMapException(get_class($entity), $key);
        }
        $property->setAccessible(true);
        return $property;
    }
}