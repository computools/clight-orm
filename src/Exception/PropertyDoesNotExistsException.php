<?php

namespace Computools\CLightORM\Exception;

class PropertyDoesNotExistsException extends AbstractException
{
	protected const MESSAGE = 'Property "%s" (%s) does not exists or has no getter/setter or has no public access';

	public function __construct(string $entityClass, string $field)
	{
		parent::__construct(
			sprintf(
				self::MESSAGE,
				$field,
				$entityClass
			)
		);
	}
}