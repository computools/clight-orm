<?php

namespace Computools\CLightORM\Entity;

use Computools\CLightORM\{
	Exception\EntityFieldDoesNotExistsException,
	Mapper\Relations\ManyToMany,
	Mapper\Relations\RelationInterface
};

abstract class AbstractEntity implements EntityInterface
{
	const RELATION_CHANGE_ADD = 'add';
	const RELATION_CHANGE_REMOVE = 'remove';

	private $relationChanges = [];

	private function relatedEntityExists(array $list, EntityInterface $entity): bool
	{
		foreach ($list as $existedEntity) {
			if ($existedEntity->getId() === $entity->getId()) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get list of relation changes (use for many-to-many)
	 *
	 * @return array
	 */
	public function getRelationChanges(): array
	{
		return $this->relationChanges;
	}

	/**
	 * Defines if entity is new
	 *
	 * @return bool
	 */
	public function isNew(): bool
	{
		return $this->getId() ? false : true;
	}

	private function addRelationChange(string $relation, string $type, int $id): void
	{
		if (!isset($this->relationChanges[$relation])) {
			$this->relationChanges[$relation] = [];
		}
		if (!isset($this->relationChanges[$relation][$type])) {
			$this->relationChanges[$relation][$type] = [];
		}

		$this->relationChanges[$relation][$type][] = $id;
	}

	private function getEntityRelationField(EntityInterface $entity): array
	{
		foreach($this->getMapper()->getFields() as $entityFieldName => $field) {
			if ($field instanceof RelationInterface && $field instanceof ManyToMany && $field->getEntityClass() === get_class($entity)) {
				return [$entityFieldName, $field];
			}
		}
		throw new EntityFieldDoesNotExistsException($entity, $this);
	}

	/**
	 * This method must be used to add many-to-many relation
	 */
	public function addRelation(EntityInterface $entity): void
	{
		list($relation, $relationType) = $this->getEntityRelationField($entity);
		list($setter, $getter) = $this->getMapper()->defineMethodNames($relation);

		if (!$list = $this->$getter()) {
			$list = [];
		}

		if (!$this->relatedEntityExists($list, $entity)) {
			$list[] = $entity;
			$this->$setter($list);
		}
		$this->addRelationChange($relation, self::RELATION_CHANGE_ADD, $entity->getId());
	}

	/**
	 * This method must be used to remove many-to-many relation
	 */
	public function removeRelation(EntityInterface $entity): void
	{
		list($relation, $relationType) = $this->getEntityRelationField($entity);
		list($setter, $getter) = $this->getMapper()->defineMethodNames($relation);

		$list = $this->$getter();
		foreach($list as $key => $existedEntity) {
			if ($existedEntity->getId() === $entity->getId()) {
				unset($list[$key]);
			}
		}

		$this->$setter($list);

		$this->addRelationChange($relation, self::RELATION_CHANGE_REMOVE, $entity->getId());
	}
}