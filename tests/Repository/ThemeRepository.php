<?php

namespace Computools\CLightORM\Test\Repository;

use Computools\CLightORM\Repository\AbstractRepository;
use Computools\CLightORM\Test\Entity\Theme;

class ThemeRepository extends AbstractRepository
{
	public function getEntityClass(): string
	{
		return Theme::class;
	}
}