<?php

namespace Computools\CLightORM\Test\Repository;

use Computools\CLightORM\Database\Query\Structure\Join;
use Computools\CLightORM\Repository\AbstractRepository;
use Computools\CLightORM\Test\Entity\Post;

class PostRepository extends AbstractRepository
{
	public function getEntityClass(): string
	{
		return Post::class;
	}

	public function getPostsWithCategoriesCount()
    {
        $query = $this->orm->createQuery();
        $query
            ->from($this->table, 'p')
            ->select('p.*, count(c.post_id) AS categories_count')
            ->join(new Join('left', 'categorization', 'ON c.post_id = p.id', 'c'))
            ->groupBy('p.id')
            ->orderBy('p.id')
            ->execute();
        return $this->mapToEntities($query);
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