<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\MapperInterface;
use Computools\CLightORM\Test\Mapper\PostMapper;

class Post extends AbstractEntity
{
	public function getMapper(): MapperInterface
	{
		return new PostMapper();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	private $id;

	private $author;

	private $editor;

	private $isPublished;

	private $datePublished;

	private $title;

	private $categories;

	public function getAuthor(): ?User
	{
		return $this->author;
	}

	public function setAuthor($author)
	{
		$this->author = $author;
	}

	public function getEditor()
	{
		return $this->editor;
	}

	public function setEditor($editor)
	{
		$this->editor = $editor;
	}

	public function getDatePublished()
	{
		return $this->datePublished;
	}

	public function setDatePublished($datePublished)
	{
		$this->datePublished = $datePublished;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getIsPublished()
	{
		return $this->isPublished;
	}

	public function setIsPublished($isPublished)
	{
		$this->isPublished = $isPublished;
	}

	/**
	 * @return Category[]
	 */
	public function getCategories()
	{
		return $this->categories;
	}

	public function setCategories(?array $categories): void
	{
		$this->categories = $categories;
	}
}