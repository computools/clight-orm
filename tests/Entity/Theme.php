<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\MapperInterface;
use Computools\CLightORM\Test\Mapper\ThemeMapper;

class Theme extends AbstractEntity
{
	public function getMapper(): MapperInterface
	{
		return new ThemeMapper();
	}

	private $id;

	private $title;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title)
	{
		$this->name = $title;
	}
}