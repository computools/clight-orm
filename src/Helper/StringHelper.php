<?php

namespace Computools\CLightORM\Helper;

class StringHelper
{
    public static function toCamelCase(string $sting, $lowerFirst = true): string
    {
        $fieldParts = array_map(
            function ($item) {
                return ucfirst($item);
            },
            explode('_', $sting)
        );
        $camelCase = implode('', $fieldParts);
        return $lowerFirst ? lcfirst($camelCase) : $camelCase;
    }

    public static function toUnderScore(string $string): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_$0', $string));
    }

    public static function getStringOptions(string $string): array
    {
        return [
            self::toCamelCase($string),
            self::toUnderScore($string)
        ];
    }
}