<?php

namespace Computools\CLightORM\Test\Mapper;

use Computools\CLightORM\Mapper\Mapper;
use Computools\CLightORM\Mapper\Relations\ManyToMany;
use Computools\CLightORM\Mapper\Relations\ManyToOne;
use Computools\CLightORM\Mapper\Types\BooleanType;
use Computools\CLightORM\Mapper\Types\DateTimeType;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;
use Computools\CLightORM\Test\Entity\Category;
use Computools\CLightORM\Test\Entity\User;

class PostMapper extends Mapper
{
	const DATETIME_FORMAT = 'Y-m-d';

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
			'categories' => new ManyToMany(new Category(), 'categorization', 'post_id', 'category_id')
		];
	}
}