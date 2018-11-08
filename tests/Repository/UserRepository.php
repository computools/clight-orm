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

	public function testFind()
	{
		$query = $this->orm->createQuery();
		$query
			->select('*')
			->from('users')
			->whereExpr('id < 3');
		$query->execute();
		return $this->mapToEntities($query, ['postsAsAuthor', 'posts_as_editor']);
	}
}