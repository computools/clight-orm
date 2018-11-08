<?php

namespace Computools\CLightORM\Mapper\Types;

use Computools\CLightORM\Entity\EntityInterface;

class UpdatedAtType extends DateTimeType
{
    public function serialize($value, EntityInterface $entity)
    {
        return (new \DateTime())->format($this->getFormat());
    }
}