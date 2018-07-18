<?php

namespace Computools\CLightORM\Entity;

use Computools\CLightORM\{
	Exception\EntityFieldDoesNotExistsException, Exception\PropertyDoesNotExistsException, Mapper\Relations\ManyToMany, Mapper\Relations\RelationInterface
};

abstract class AbstractEntity implements EntityInterface
{
	const RELATION_CHANGE_ADD = 'add';
	const RELATION_CHANGE_REMOVE = 'remove';

	private $relationChanges = [];

	public function getIdValue(): ?int
	{
		return $this->getField($this->getMapper()->getIdentifierEntityField());
	}

	private function relatedEntityExists(array $list, EntityInterface $entity): bool
	{
		/**
		 * @var EntityInterface $existedEntity
		 */
		foreach ($list as $existedEntity) {
			if ($existedEntity->getIdValue() === $entity->getIdValue()) {
				return true;
			}
		}
		return false;
	}

	public function getField(string $field)
	{
		try {
			if (method_exists($this, $getter = $this->getMapper()->defineGetterName($field))) {
				return $this->$getter();
			}
			if (property_exists($this, $property = $this->getMapper()->defineGetterName($field, false))) {
				return $this->$property;
			}
		} catch (\Throwable $e) {
			throw new PropertyDoesNotExistsException(get_class($this), $field);
		}
		throw new PropertyDoesNotExistsException(get_class($this), $field);
	}

	public function setField(string $field, $value = null)
	{
		try {
			$reflectionObject = new \ReflectionClass($this);
			if (method_exists($this, $setter = $this->getMapper()->defineSetterName($field))) {
				if ($reflectionObject->getMethod($setter)->isPublic()) {
					$this->$setter($value);
					return true;
				}
			}
			if (property_exists($this, $property = $this->getMapper()->defineSetterName($field, false))) {
				if ($reflectionObject->getProperty($property)->isPublic()) {
					$this->$property = $value;
					return true;
				}
			}
		} catch (\Throwable $e) {
			throw $e;
		}
		throw new PropertyDoesNotExistsException(get_class($this), $field);
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
		return $this->getField($this->getMapper()->getIdentifierEntityField()) ? false : true;
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
		foreach ($this->getMapper()->getFields() as $entityFieldName => $field) {
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

		if (!$list = $this->getField($relation)) {
			$list = [];
		}

		if (!$this->relatedEntityExists($list, $entity)) {
			$list[] = $entity;
			$this->setField($relation, $list);
		}
		$this->addRelationChange($relation, self::RELATION_CHANGE_ADD, $entity->getIdValue());
	}

	/**
	 * This method must be used to remove many-to-many relation
	 */
	public function removeRelation(EntityInterface $entity): void
	{
		list($relation, $relationType) = $this->getEntityRelationField($entity);
		$list = $this->getField($relation);

		/**
		 * @var EntityInterface $existedEntity
		 */
		foreach ($list as $key => $existedEntity) {
			if ($existedEntity->getIdValue() === $entity->getIdValue()) {
				unset($list[$key]);
			}
		}

		$this->setField($relation, $list);

		$this->addRelationChange($relation, self::RELATION_CHANGE_REMOVE, $entity->getIdValue());
	}
}