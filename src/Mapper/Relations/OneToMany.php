<?php

namespace Computools\CLightORM\Mapper\Relations;

use Computools\CLightORM\Entity\EntityInterface;

class OneToMany implements RelationInterface, ToManyInterface
{
	private $entity;

	private $relatedTableField;

	public function __construct(EntityInterface $entity, string $relatedTableField)
	{
		$this->entity = $entity;
		$this->relatedTableField = $relatedTableField;
	}

	public function getRelatedEntity(): EntityInterface
	{
		return $this->entity;
	}

	public function getEntityClass(): string
	{
		return get_class($this->entity);
	}

	public function getRelatedTableField()
	{
		return $this->relatedTableField;
	}
}