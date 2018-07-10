<?php

namespace Computools\CLightORM\Test\Mapper;

use Computools\CLightORM\Mapper\Mapper;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;

class ThemeMapper extends Mapper
{
	public function getTable(): string
	{
		return 'theme';
	}

	public function getFields(): array
	{
		return [
			'id' => new IdType(),
			'title' => new StringType()
		];
	}
}