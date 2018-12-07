<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\Relations\ManyToMany;
use Computools\CLightORM\Mapper\Relations\ManyToOne;
use Computools\CLightORM\Mapper\Types\BooleanType;
use Computools\CLightORM\Mapper\Types\DateTimeType;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\IntegerType;
use Computools\CLightORM\Mapper\Types\StringType;

class Post extends AbstractEntity
{
    public function getTable(): string
    {
        return 'post';
    }

    public function getFields(): array
    {
        return [
            'id' => new IdType(),
            'author' => new ManyToOne(new User(), 'author_id'),
            'editor' => new ManyToOne(new User(), 'editor_id'),
            'is_published' => new BooleanType(),
            'date_published' => new DateTimeType(),
            'title' => new StringType('post_title'),
            'categories' => new ManyToMany(new Category(), 'categorization', 'post_id', 'category_id'),
        ];
    }

    public function getOptionalFields(): array
    {
        return [
            'categories_count' => new IntegerType()
        ];
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

	private $categoriesCount;

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

    /**
     * @return mixed
     */
    public function getCategoriesCount(): ?int
    {
        return $this->categoriesCount;
    }
}