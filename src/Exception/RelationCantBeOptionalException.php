<?php

namespace Computools\CLightORM\Exception;

class RelationCantBeOptionalException extends AbstractException
{
    protected const MESSAGE = 'Relation can\'t be optional';

    public function __construct(string $entityClass, ?string $field = null)
    {
        $field = $field ? " ($field)" : '';
        parent::__construct(self::MESSAGE . $entityClass . $field);
    }
}