<?php

namespace Computools\CLightORM\Entity;

use Computools\CLightORM\{
    Exception\EntityFieldDoesNotExistsException, Exception\EntityRelationDoesNotExistsException, Exception\IdentifierDoesNotExistsException, Mapper\FieldMapStorage, Mapper\Relations\ManyToMany, Mapper\Relations\RelationChangesList, Mapper\Relations\RelationInterface, Mapper\Relations\ToOneInterface, Mapper\Types\IdType
};
use Computools\CLightORM\Helper\ReflectionHelper;

abstract class AbstractEntity implements EntityInterface
{
    abstract public function getTable(): string;

    abstract public function getFields(): array;

    public function getOptionalFields(): array
    {
        return [];
    }

    final public function getIdentifierFromArray(array $data): ?int
    {
        return $data[$this->getIdentifier()] ?? null;
    }

    final public function getIdentifierEntityField(): string
    {
        foreach(FieldMapStorage::getFields($this) as $key => $value) {
            if ($value instanceof IdType) {
                return $key;
            }
        }
        throw new IdentifierDoesNotExistsException();
    }

    final public function getIdentifier(): string
    {
        foreach(FieldMapStorage::getFields($this) as $key => $value) {
            if ($value instanceof IdType) {
                if ($value->getIdentifierField()) {
                    return $value->getIdentifierField();
                }
                return $key;
            }
        }
        throw new IdentifierDoesNotExistsException();
    }

    /**
     * @var RelationChangesList
     */
	protected $relationChangesList;

	protected $allowedFields = [];

	protected $originalData = [];

	protected $referencesToDestroy = [];

	final public function getOriginalData(): array
    {
        return $this->originalData;
    }

    final public function setOriginalData(array $originalData): EntityInterface
    {
        $this->originalData = $originalData;
        return $this;
    }

    final public function fill(array $values): EntityInterface
    {
        foreach ($values as $key => $value) {
            if (in_array($key, $this->allowedFields)) {
                ReflectionHelper::setEntityProperty($this, $key, $value);
            }
        }
        return $this;
    }

	final public function getIdValue(): ?int
	{
		return ReflectionHelper::getEntityProperty($this, $this->getIdentifierEntityField());
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
	final public function getRelationChangesList(): RelationChangesList
	{
	    if (!$this->relationChangesList) {
	        $this->relationChangesList = new RelationChangesList();
        }
		return $this->relationChangesList;
	}

	/**
	 * Defines if entity is new
	 *
	 * @return bool
	 */
	final public function isNew(): bool
	{
		return ReflectionHelper::getEntityProperty($this, $this->getIdentifierEntityField()) ? false : true;
	}

	private function changeRelation(string $relation, string $type, int $id): void
	{
	    if (!$this->relationChangesList) {
	        $this->relationChangesList = new RelationChangesList();
        }

        $this->relationChangesList->addToManyChange($relation, $type, $id);
	}

	private function getEntityRelationField(EntityInterface $entity): array
	{
		foreach (FieldMapStorage::getFields($this) as $entityFieldName => $field) {
			if ($field instanceof RelationInterface && $field instanceof ManyToMany && $field->getEntityClass() === get_class($entity)) {
				return [$entityFieldName, $field];
			}
		}
		throw new EntityFieldDoesNotExistsException($entity, $this);
	}

	private function getEntityRelationByKey(string $key): array
    {
        foreach (FieldMapStorage::getFields($this) as $entityFieldName => $field) {
            if ($key === $entityFieldName && $field instanceof ToOneInterface) {
                return [
                    $entityFieldName,
                    $field->getFieldName()
                ];
            }
        }
        throw new EntityRelationDoesNotExistsException($key, $this);
    }

	final public function destroyToOneRelation(string $field): EntityInterface
    {
        if (!$this->relationChangesList) {
            $this->relationChangesList = new RelationChangesList();
        }
        list($entityField, $tableField) = $this->getEntityRelationByKey($field);
        $this->relationChangesList->addToOneChange($entityField, $tableField);
        ReflectionHelper::setEntityProperty($this, $field, null);
        return $this;
    }

	/**
	 * This method must be used to add many-to-many relation
	 */
	final public function addRelation(EntityInterface $entity): EntityInterface
	{
		list($relation, $relationType) = $this->getEntityRelationField($entity);

		if (!$list = ReflectionHelper::getEntityProperty($this, $relation)) {
			$list = [];
		}

		if (!$this->relatedEntityExists($list, $entity)) {
			$list[] = $entity;
			ReflectionHelper::setEntityProperty($this, $relation, $list);
		}

		$this->changeRelation($relation, RelationChangesList::RELATION_CHANGE_ADD, $entity->getIdValue());
		return $this;
	}

	/**
	 * This method must be used to remove many-to-many relation
	 */
	final public function removeRelation(EntityInterface $entity): EntityInterface
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

		$this->changeRelation($relation, RelationChangesList::RELATION_CHANGE_REMOVE, $entity->getIdValue());

		return $this;
	}
}