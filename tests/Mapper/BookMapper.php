<?php

namespace Computools\CLightORM\Test\Mapper;

use Computools\CLightORM\Mapper\Mapper;
use Computools\CLightORM\Mapper\Relations\ManyToMany;
use Computools\CLightORM\Mapper\Types\FloatType;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;
use Computools\CLightORM\Test\Entity\Author;
use Computools\CLightORM\Test\Entity\Theme;

class BookMapper extends Mapper
{
	public function getTable(): string
	{
		return 'books';
	}

	public function getFields(): array
	{
		return [
			'id' => new IdType(),
			'name' => new StringType('title'),
			'price' => new FloatType(),
			'authors' => new ManyToMany(new Author(), 'authors_books', 'book_id', 'author_id'),
			'themes' => new ManyToMany(new Theme(), 'books_theme', 'book_id', 'theme_id')
		];
	}
}