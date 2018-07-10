<?php

namespace Computools\CLightORM\Test\Mapper;

use Computools\CLightORM\Mapper\Mapper;
use Computools\CLightORM\Mapper\Relations\OneToOne;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;
use Computools\CLightORM\Test\Entity\User;

class UserProfileMapper extends Mapper
{
	public function getTable(): string
	{
		return 'user_profile';
	}

	public function getFields(): array
	{
		return [
			'id' => new IdType(),
			'user' => new OneToOne(new User(), 'user_id'),
			'first_name' => new StringType(),
			'last_name' => new StringType()
		];
	}
}