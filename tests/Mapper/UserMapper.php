<?php

namespace Computools\CLightORM\Test\Mapper;

use Computools\CLightORM\Mapper\Mapper;
use Computools\CLightORM\Mapper\Relations\OneToMany;
use Computools\CLightORM\Mapper\Relations\OneToOne;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;
use Computools\CLightORM\Test\Entity\Post;
use Computools\CLightORM\Test\Entity\UserProfile;

class UserMapper extends Mapper
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
			'posts_as_author' => new OneToMany(new Post(), 'author_id'),
			'posts_as_editor' => new OneToMany(new Post(), 'editor_id'),
			'profile' => new OneToOne(new UserProfile(), 'profile_id')
		];
	}
}