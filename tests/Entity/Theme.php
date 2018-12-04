<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;

class Theme extends AbstractEntity
{
    public function getTable(): string
    {
        return 'theme';
    }

    public function getFields(): array
    {
        return [
            'id' => new IdType(),
            'title' => new StringType()
        ];
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