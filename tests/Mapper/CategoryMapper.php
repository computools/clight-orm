<?php

namespace Computools\CLightORM\Test\Mapper;

use Computools\CLightORM\Mapper\Mapper;
use Computools\CLightORM\Mapper\Relations\ManyToMany;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;
use Computools\CLightORM\Test\Entity\Post;

class CategoryMapper extends Mapper
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
}