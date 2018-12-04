<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\Relations\ManyToMany;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;

class Author extends AbstractEntity
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

    private $id;

	private $name;

	private $books;

	/**
	 * @return Book[]
	 */
	public function getBooks()
	{
		return $this->books;
	}

	public function setBooks($books)
	{
		$this->books = $books;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
}