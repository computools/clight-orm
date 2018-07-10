<?php

namespace Computools\CLightORM\Test\Repository;

use Computools\CLightORM\Repository\AbstractRepository;
use Computools\CLightORM\Test\Entity\Author;

class AuthorRepository extends AbstractRepository
{
	public function getEntityClass(): string
	{
		return Author::class;
	}
}