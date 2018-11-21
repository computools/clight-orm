<?php

namespace Computools\CLightORM\Exception;

use Computools\CLightORM\Entity\EntityInterface;

class EntityRelationDoesNotExistsException extends AbstractException
{
    protected const MESSAGE = 'Entity %s does not have relation called %s';

    public function __construct(string $field, EntityInterface $entity)
    {
        parent::__construct(sprintf(self::MESSAGE, get_class($entity), $field));
    }
}