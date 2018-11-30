<?php

namespace Computools\CLightORM\Test\Repository;

use Computools\CLightORM\Repository\AbstractRepository;
use Computools\CLightORM\Test\Entity\Post;

class PostRepository extends AbstractRepository
{
	public function getEntityClass(): string
	{
		return Post::class;
	}

	public function findLastNative()
	{
		$query = $this->orm->createQuery();
		$query->from($this->table);
		$query->orderBy('id', 'ASC');
		$query->limit(1);
		$query->execute();
		return $this->mapToEntity($query, ['author', 'editor']);
	}

	public function findFirstNative()
	{
		$query = $this->orm->createQuery();
		$query
			->from($this->table)
			->whereExpr('id < :id')
			->limit(1)
			->execute(['id' => 6]);
		return $this->mapToEntity($query, ['author']);
	}

	public function findByNativeQuery()
	{
		$query = $this->orm->createNativeQuery();
		$query->setQuery("SELECT * FROM post WHERE id=:id;");
		$query->addParameter('id', 6);
		$query->execute();
		return $this->mapToEntity($query, ['author' => ['posts_as_author']]);
	}
}