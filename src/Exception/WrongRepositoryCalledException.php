<?php

namespace Computools\CLightORM\Exception;

class WrongRepositoryCalledException extends AbstractException
{
	protected const MESSAGE = 'Wrong repository called for entity operations';
}