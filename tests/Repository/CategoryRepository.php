<?php

namespace Computools\CLightORM\Test\Repository;

use Computools\CLightORM\Repository\AbstractRepository;
use Computools\CLightORM\Test\Entity\Category;

class CategoryRepository extends AbstractRepository
{
	public function getEntityClass(): string
	{
		return Category::class;
	}
}