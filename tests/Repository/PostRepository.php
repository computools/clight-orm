<?php

namespace Computools\CLightORM\Test\Repository;

use Computools\CLightORM\Repository\AbstractRepository;
use Computools\CLightORM\Test\Entity\Post;
use Computools\CLightORM\Test\Entity\User;

class PostRepository extends AbstractRepository
{
	public function getEntityClass(): string
	{
		return Post::class;
	}

	public function findByAuthor(User $user, $with = []): array
	{
		$query = $this->database->table(
			$this->entity->getMapper()->getTable()
		)->where('author_id', $user->getId());
		return $this->mapToEntities($query->fetchAll(), $query, $with);
	}

	public function findByAuthorNative(User $user): array
	{
		$query = $this->database->prepare('
			SELECT * FROM post WHERE author_id = :authorId
		');
		$query->execute([
			'authorId' => $user->getId()
		]);

		$result = [];
		foreach ($query->fetchAll() as $row) {
			$result[] = $this->entity->getMapper()->arrayToEntity(new Post(), $row);
		}
		return $result;
	}
}