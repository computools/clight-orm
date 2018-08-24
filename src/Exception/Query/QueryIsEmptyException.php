<?php

namespace Computools\CLightORM\Exception\Query;

use Computools\CLightORM\Exception\AbstractException;

class QueryIsEmptyException extends AbstractException
{
	protected const MESSAGE = 'Query is empty';
}