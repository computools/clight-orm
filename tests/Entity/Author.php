<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Test\Mapper\AuthorMapper;
use Computools\CLightORM\Mapper\MapperInterface;

class Author extends AbstractEntity
{
	public function getMapper(): MapperInterface
	{
		return new AuthorMapper();
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