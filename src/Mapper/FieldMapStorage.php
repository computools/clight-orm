<?php

namespace Computools\CLightORM\Mapper;

use Computools\CLightORM\Entity\EntityInterface;
use Computools\CLightORM\Exception\InvalidFieldMapException;
use Computools\CLightORM\Helper\StringHelper;

class FieldMapStorage
{
    private static $fieldMap = [];

    private static $optionalFieldMap = [];

    private static $mappedFieldNames = [];

    private static $reflectionProperties = [];

    public static function getReflectionProperty(EntityInterface $entity, string $key): \ReflectionProperty
    {
        if (!(self::$reflectionProperties[get_class($entity)] ?? null)) {
            self::$reflectionProperties[get_class($entity)] = [];
        }

        if (!(self::$reflectionProperties[get_class($entity)][$key] ?? null)) {
            $reflection = new \ReflectionClass($entity);
            list($camelCase, $underScore) = self::getMappedFieldNames($entity, $key);
            if ($reflection->hasProperty($camelCase)) {
                $property = $reflection->getProperty($camelCase);
            } else if ($reflection->hasProperty($underScore)) {
                $property = $reflection->getProperty($underScore);
            } else {
                throw new InvalidFieldMapException(get_class($entity), $key);
            }
            $property->setAccessible(true);
            self::$reflectionProperties[get_class($entity)][$key] = $property;
        }

        return self::$reflectionProperties[get_class($entity)][$key];
    }

    public static function getFields(EntityInterface $entity): array
    {
        if (!(self::$fieldMap[get_class($entity)] ?? null)) {
            self::$fieldMap[get_class($entity)] = array_merge($entity->getFields());
        }
        return self::$fieldMap[get_class($entity)];
    }

    public static function getOptionalFields(EntityInterface $entity): array
    {
        if (!(self::$optionalFieldMap[get_class($entity)] ?? null)) {
            self::$optionalFieldMap[get_class($entity)] = array_merge($entity->getOptionalFields());
        }
        return self::$optionalFieldMap[get_class($entity)];
    }

    public static function getMappedFieldNames(EntityInterface $entity, string $key): array
    {
        if (!(self::$mappedFieldNames[get_class($entity)][$key] ?? null)) {
            if (!(self::$mappedFieldNames[get_class($entity)] ?? null)) {
                self::$mappedFieldNames[get_class($entity)] = [];
            }
            self::$mappedFieldNames[get_class($entity)][$key] = StringHelper::getStringOptions($key);
        }
        return self::$mappedFieldNames[get_class($entity)][$key];
    }
}