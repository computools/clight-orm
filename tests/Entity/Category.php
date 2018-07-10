<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\MapperInterface;
use Computools\CLightORM\Test\Mapper\CategoryMapper;

class Category extends AbstractEntity
{
	public function getMapper(): MapperInterface
	{
		return new CategoryMapper();
	}

	private $id;

	private $title;

	private $posts;

	/**
	 * @return mixed
	 */
	public function getPosts()
	{
		return $this->posts;
	}

	/**
	 * @param mixed $posts
	 */
	public function setPosts($posts)
	{
		$this->posts = $posts;
	}


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

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}
}