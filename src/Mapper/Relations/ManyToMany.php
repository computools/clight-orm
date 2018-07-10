<?php

namespace Computools\CLightORM\Mapper\Relations;

use Computools\CLightORM\Entity\EntityInterface;

class ManyToMany implements RelationInterface, ToManyInterface
{
	private $relatedEntity;

	private $table;

	private $fields;

	private $columnName;

	private $referencedColumnName;

	public function __construct(EntityInterface $entity, string $table, string $columnName, string $referencedColumnName)
	{
		$this->relatedEntity = $entity;
		$this->table = $table;
		$this->columnName = $columnName;
		$this->referencedColumnName = $referencedColumnName;
		$this->fields = [
			$columnName,
			$referencedColumnName
		];
	}

	public function getRelatedEntity(): EntityInterface
	{
		return $this->relatedEntity;
	}

	public function getTable(): string
	{
		return $this->table;
	}

	public function getFields(): array
	{
		return $this->fields;
	}

	public function getEntityClass(): string
	{
		return get_class($this->relatedEntity);
	}

	public function getColumnName(): string
	{
		return $this->columnName;
	}

	public function getReferencedColumnName(): string
	{
		return $this->referencedColumnName;
	}
}