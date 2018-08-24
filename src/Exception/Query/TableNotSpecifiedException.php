<?php

namespace Computools\CLightORM\Exception\Query;

use Computools\CLightORM\Exception\AbstractException;

class TableNotSpecifiedException extends AbstractException
{
	protected const MESSAGE = 'Table was not specified';
}