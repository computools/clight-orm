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
		$query = $this->database->createQuery();
		$query
			->select('*')
			->from('users')
			->where('id < 3');
		$this->database->executeQuery($query);
		return $this->mapToEntities($query, ['posts_as_author', 'posts_as_editor']);
	}
}