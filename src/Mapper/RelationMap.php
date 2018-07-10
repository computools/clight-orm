<?php

namespace Computools\CLightORM\Mapper;

use Computools\CLightORM\Mapper\Relations\RelationInterface;

class RelationMap
{
	private $tableName;

	private $entityField;

	private $relationType;

	private $options;

	private $parentIdentifier;

	public function __construct(string $tableName, string $entityField, RelationInterface $relation, string $parentIdentifier = 'id', $options = [])
	{
		$this->tableName = $tableName;
		$this->entityField = $entityField;
		$this->relationType = $relation;
		$this->parentIdentifier = $parentIdentifier;
		$this->options = $options;
	}

	public function getTableName(): string
	{
		return $this->tableName;
	}

	public function getEntityField(): string
	{
		return $this->entityField;
	}

	public function getRelationType(): RelationInterface
	{
		return $this->relationType;
	}

	public function getOptions(): array
	{
		return $this->options;
	}

	public function getParentIdentifier(): string
	{
		return $this->parentIdentifier;
	}
}