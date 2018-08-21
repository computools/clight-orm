<?php

namespace Computools\CLightORM\Exception;

class QueryException extends AbstractException
{
	protected $message = 'Database query exception';

	public function __construct(array $errorInfo = [])
	{
		parent::__construct($errorInfo ? implode(': ', $errorInfo) : $this->message);
	}
}