<?php

namespace Computools\CLightORM\Test\Repository;

use Computools\CLightORM\Repository\AbstractRepository;
use Computools\CLightORM\Test\Entity\Book;

class BookRepository extends AbstractRepository
{
	public function getEntityClass(): string
	{
		return Book::class;
	}
}