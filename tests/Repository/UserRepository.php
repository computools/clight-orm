<?php

namespace Computools\CLightORM\Test\Repository;

use Computools\CLightORM\Repository\AbstractRepository;
use Computools\CLightORM\Test\Entity\User;

class UserRepository extends AbstractRepository
{
	public function getEntityClass(): string
	{
		return User::class;
	}
}