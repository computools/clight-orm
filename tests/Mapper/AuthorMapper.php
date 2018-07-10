<?php

namespace Computools\CLightORM\Test\Mapper;

use Computools\CLightORM\Mapper\Mapper;
use Computools\CLightORM\Mapper\Relations\ManyToMany;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;
use Computools\CLightORM\Test\Entity\Book;

class AuthorMapper extends Mapper
{
	public function getTable(): string
	{
		return 'authors';
	}

	public function getFields(): array
	{
		return [
			'id' => new IdType('id'),
			'name' => new StringType(),
			'books' => new ManyToMany(new Book(), 'authors_books', 'author_id', 'book_id')
		];
	}
}