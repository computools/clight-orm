<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\MapperInterface;
use Computools\CLightORM\Mapper\Relations\OneToMany;
use Computools\CLightORM\Mapper\Relations\OneToOne;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;

class User extends AbstractEntity
{
    public function getTable(): string
    {
        return 'users';
    }

    public function getFields(): array
    {
        return [
            'id' => new IdType(),
            'name' => new StringType(),
            'postsAsAuthor' => new OneToMany(new Post(), 'author_id'),
            'posts_as_editor' => new OneToMany(new Post(), 'editor_id'),
            'profile' => new OneToOne(new UserProfile(), 'profile_id')
        ];
    }

	private $id;

	private $name;

	private $posts_as_author;

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
		return $this->posts_as_author;
	}

	public function setPostsAsAuthor($postsAsAuthor)
	{
		$this->posts_as_author = $postsAsAuthor;
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