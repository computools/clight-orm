<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\Relations\ManyToMany;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;

class Category extends AbstractEntity
{
    public function getFields(): array
    {
        return [
            'id' => new IdType(),
            'title' => new StringType(),
            'posts' => new ManyToMany(new Post(), 'categorization', 'category_id', 'post_id')
        ];
    }

    public function getTable(): string
    {
        return 'category';
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