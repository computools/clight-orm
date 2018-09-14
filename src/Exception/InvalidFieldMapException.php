<?php

namespace Computools\CLightORM\Exception;

class InvalidFieldMapException extends AbstractException
{
	protected const MESSAGE = 'Invalid field map for entity: ';

	public function __construct(string $entityClass, ?string $field = null)
	{
		$field = $field ? " ($field)" : '';
		parent::__construct(self::MESSAGE . $entityClass . $field);
	}
}