<?php

namespace Computools\CLightORM\Mapper;

use Computools\CLightORM\Entity\EntityInterface;
use Computools\CLightORM\Helper\StringHelper;

class FieldMapStorage
{
    private static $fieldMap = [];

    private static $mappedFieldNames = [];

    public static function getFields(EntityInterface $entity)
    {
        if (!(self::$fieldMap[get_class($entity)] ?? null)) {
            self::$fieldMap[get_class($entity)] = $entity->getFields();
        }
        return self::$fieldMap[get_class($entity)];
    }

    public static function getMappedFieldNames(EntityInterface $entity, string $key)
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