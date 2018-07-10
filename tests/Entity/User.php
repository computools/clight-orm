<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\MapperInterface;
use Computools\CLightORM\Test\Mapper\UserMapper;

class User extends AbstractEntity
{
	private $id;

	private $name;

	private $postsAsAuthor;

	private $postsAsEditor;

	private $profile;

	public function getProfile()
	{
		return $this->profile;
	}

	public function setProfile($profile)
	{
		$this->profile = $profile;
	}

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

	public function setName(string $name)
	{
		$this->name = $name;
	}

	public function getMapper(): MapperInterface
	{
		return new UserMapper();
	}

	/**
	 * @return Post[]
	 */
	public function getPostsAsAuthor()
	{
		return $this->postsAsAuthor;
	}

	public function setPostsAsAuthor($postsAsAuthor)
	{
		$this->postsAsAuthor = $postsAsAuthor;
	}

	public function getPostsAsEditor()
	{
		return $this->postsAsEditor;
	}

	public function setPostsAsEditor($postsAsEditor)
	{
		$this->postsAsEditor = $postsAsEditor;
	}
}