<?php

namespace Computools\CLightORM\Mapper\Relations;

use Computools\CLightORM\Entity\EntityInterface;

class ManyToOne implements RelationInterface, ToOneInterface
{
	protected $relatedEntity;

	protected $fieldName;

	public function __construct(EntityInterface $entity, string $fieldName)
	{
		$this->relatedEntity = $entity;
		$this->fieldName = $fieldName;
	}

	public function getFieldName(): string
	{
		return $this->fieldName;
	}

	public function getRelatedEntity(): EntityInterface
	{
		return $this->relatedEntity;
	}

	public function getEntityClass(): string
	{
		return get_class($this->relatedEntity);
	}
}