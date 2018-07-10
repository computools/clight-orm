<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\MapperInterface;
use Computools\CLightORM\Test\Mapper\BookMapper;

class Book extends AbstractEntity
{
	public function getMapper(): MapperInterface
	{
		return new BookMapper();
	}

	private $id;

	private $name;

	private $authors;

	private $themes;

	private $price;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(?string $name): void
	{
		$this->name = $name;
	}

	public function getAuthors(): ?array
	{
		return $this->authors;
	}

	public function setAuthors(?array $authors)
	{
		$this->authors = $authors;
	}


	public function getThemes(): ?array
	{
		return $this->themes;
	}

	public function setThemes(?array $themes): void
	{
		$this->themes = $themes;
	}

	public function getPrice(): ?float
	{
		return $this->price;
	}

	public function setPrice(?float $price)
	{
		$this->price = $price;
	}
}