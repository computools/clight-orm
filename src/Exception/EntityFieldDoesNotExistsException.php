<?php

namespace Computools\CLightORM\Exception;

use Computools\CLightORM\Entity\EntityInterface;

class EntityFieldDoesNotExistsException extends AbstractException
{
	protected const MESSAGE = 'Entity %s does not related as many-to-many with %s';

	public function __construct(EntityInterface $childEntity, EntityInterface $entity)
	{
		parent::__construct(sprintf(self::MESSAGE, get_class($childEntity), get_class($entity)));
	}
}