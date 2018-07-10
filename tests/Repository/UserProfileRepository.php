<?php

namespace Computools\CLightORM\Test\Repository;

use Computools\CLightORM\Repository\AbstractRepository;
use Computools\CLightORM\Test\Entity\UserProfile;

class UserProfileRepository extends AbstractRepository
{
	public function getEntityClass(): string
	{
		return UserProfile::class;
	}
}