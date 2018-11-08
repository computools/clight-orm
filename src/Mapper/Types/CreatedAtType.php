<?php

namespace Computools\CLightORM\Mapper\Types;

use Computools\CLightORM\Entity\EntityInterface;

class CreatedAtType extends DateTimeType
{
    public function serialize($value, EntityInterface $entity)
    {
        return $entity->isNew()
            ? (new \DateTime())->format($this->getFormat())
            : (
            $value
            ? $value->format($this->getFormat())
            : null
        );
    }
}