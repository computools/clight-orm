<?php

namespace Computools\CLightORM\Entity;

use Computools\CLightORM\{
	Exception\EntityFieldDoesNotExistsException, Exception\PropertyDoesNotExistsException, Mapper\Relations\ManyToMany, Mapper\Relations\RelationInterface
};
use Computools\CLightORM\Helper\ReflectionHelper;

abstract class AbstractEntity implements EntityInterface
{
	const RELATION_CHANGE_ADD = 'add';
	const RELATION_CHANGE_REMOVE = 'remove';

	private $relationChanges = [];

	protected $allowedFields = [];

	public function fill(array $values): EntityInterface
    {
        foreach ($values as $key => $value) {
            if (in_array($key, $this->allowedFields)) {
                ReflectionHelper::setEntityProperty($this, $key, $value);
            }
        }
        return $this;
    }

	public function getIdValue(): ?int
	{
		return ReflectionHelper::getEntityProperty($this, $this->getMapper()->getIdentifierEntityField());
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
		return ReflectionHelper::getEntityProperty($this, $this->getMapper()->getIdentifierEntityField()) ? false : true;
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

		if (!$list = ReflectionHelper::getEntityProperty($this, $relation)) {
			$list = [];
		}

		if (!$this->relatedEntityExists($list, $entity)) {
			$list[] = $entity;
			ReflectionHelper::setEntityProperty($this, $relation, $list);
		}
		$this->addRelationChange($relation, self::RELATION_CHANGE_ADD, $entity->getIdValue());
	}

	/**
	 * This method must be used to remove many-to-many relation
	 */
	public function removeRelation(EntityInterface $entity): void
	{
		list($relation, $relationType) = $this->getEntityRelationField($entity);
		$list = ReflectionHelper::getEntityProperty($this, $relation);

		/**
		 * @var EntityInterface $existedEntity
		 */
		foreach ($list as $key => $existedEntity) {
			if ($existedEntity->getIdValue() === $entity->getIdValue()) {
				unset($list[$key]);
			}
		}

        ReflectionHelper::setEntityProperty($this, $relation, $list);

		$this->addRelationChange($relation, self::RELATION_CHANGE_REMOVE, $entity->getIdValue());
	}
}