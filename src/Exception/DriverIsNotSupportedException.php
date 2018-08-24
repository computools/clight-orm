<?php

namespace Computools\CLightORM\Exception;

class DriverIsNotSupportedException extends AbstractException
{
	public function __construct(string $driver)
	{
		parent::__construct('Driver \'' . $driver . '\' is not supported');
	}
}