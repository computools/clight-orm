<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\Relations\ManyToMany;
use Computools\CLightORM\Mapper\Types\FloatType;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\JsonType;
use Computools\CLightORM\Mapper\Types\StringType;


class Book extends AbstractEntity
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
            'data' => new JsonType(),
            'dataBinary' => new JsonType('data_binary'),
            'authors' => new ManyToMany(new Author(), 'authors_books', 'book_id', 'author_id'),
            'themes' => new ManyToMany(new Theme(), 'books_theme', 'book_id', 'theme_id')
        ];
    }

    protected $allowedFields = [
	    'name',
        'price'
    ];

	public $id;

	public $name;

	public $authors;

	public $themes;

	public $price;

	public $data;

	public $dataBinary;
}