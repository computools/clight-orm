<?php

namespace Computools\CLightORM\Exception;

abstract class AbstractException extends \LogicException
{
	protected const MESSAGE = 'Internal server error';

	public function __construct($message = null)
	{
		parent::__construct($message ? $message : static::MESSAGE);
	}
}