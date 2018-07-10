<?php

namespace Computools\CLightORM\Mapper\Relations;

use Computools\CLightORM\Entity\EntityInterface;

interface RelationInterface
{
	public function getRelatedEntity(): EntityInterface;
	public function getEntityClass(): string;
}