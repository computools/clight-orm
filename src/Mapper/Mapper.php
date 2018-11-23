<?php

namespace Computools\CLightORM\Mapper;

use Computools\CLightORM\{
    Entity\EntityInterface, Exception\IdentifierDoesNotExistsException, Exception\InvalidFieldMapException, Exception\NestedEntityDoesNotExistsException, Helper\ReflectionHelper, Helper\StringHelper
};
use Computools\CLightORM\Mapper\Relations\{
	ManyToMany, ManyToOne, RelationInterface, ToManyInterface, ToOneInterface
};
use Computools\CLightORM\Mapper\Types\{
	ColumnType,
	IdType
};

abstract class Mapper implements MapperInterface
{
	abstract function getFields(): array;

	abstract function getTable(): string;

	final public function getIdentifierFromArray(array $data): ?int
	{
		return $data[$this->getIdentifier()] ?? null;
	}

	final public function getIdentifier(): string
	{
		foreach($this->getFields() as $key => $value) {
			if ($value instanceof IdType) {
				if ($value->getIdentifierField()) {
					return $value->getIdentifierField();
				}
				return $key;
			}
		}
		throw new IdentifierDoesNotExistsException();
	}

	final public function getIdentifierEntityField(): string
	{
		foreach($this->getFields() as $key => $value) {
			if ($value instanceof IdType) {
				return $key;
			}
		}
		throw new IdentifierDoesNotExistsException();
	}

	final public function arrayToEntity(EntityInterface $entity, array $data = null): ?EntityInterface
	{
		foreach ($entity->getMapper()->getFields() as $key => $field) {
			$entityField = $key;
			if (!$field instanceof RelationInterface) {
				$key = $field->getColumnName() ? $field->getColumnName() : $key;
				if (!array_key_exists($key, $data)) {
					throw new InvalidFieldMapException(get_class($entity), $key);
				}

				if ($field instanceof IdType) {
                    ReflectionHelper::setEntityProperty($entity, $entityField, $data[$entity->getMapper()->getIdentifier()]);
                } else {
                    ReflectionHelper::setEntityProperty($entity, $entityField, $field->unserialize($data[$key], $entity));
                }
			} else {
				$relatedEntityClass = $field->getEntityClass();
				if ($field instanceof ManyToOne) {
					if ($data[$key] ?? null) {
                        ReflectionHelper::setEntityProperty($entity, $entityField, $this->arrayToEntity(new $relatedEntityClass(), $data[$key]));
					}
				} else {
					if ($data[$key] ?? null) {
						$collection = [];
						foreach ($data[$key] as $collectionItem) {
                            $collection[] = $this->arrayToEntity(new $relatedEntityClass(), $collectionItem);
						}
                        ReflectionHelper::setEntityProperty($entity, $entityField, $collection);
					} else if ($field instanceof ToManyInterface) {
						ReflectionHelper::setEntityProperty($entity, $entityField, []);
					}
				}
			}
		}
		return $entity;
	}

	private function mapBaseTypeToArray(EntityInterface $entity, ColumnType $columnType, string $key, array &$result): void
	{
		$entityField = $key;
		$key = $columnType->getColumnName() ? $columnType->getColumnName() : $key;

		$entityValue = ReflectionHelper::getEntityProperty($entity, $entityField);
        $result[$key] = $columnType->serialize($entityValue, $entity);
	}

	private function mapRelationToArray(EntityInterface $entity, RelationInterface $relation, string $key, array &$result): void
	{
		$entityField = $key;
		if ($relation instanceof ToOneInterface) {
			$nestedEntity = ReflectionHelper::getEntityProperty($entity, $entityField);
			if ($nestedEntity) {
				if (!$id = $nestedEntity->getIdValue()) {
					throw new NestedEntityDoesNotExistsException();
				}
				$result[$relation->getFieldName()] = $id;
			}
		} else if ($relation instanceof ManyToMany) {
			$relatedObjects = ReflectionHelper::getEntityProperty($entity, $entityField);

			if (!empty($relatedObjects)) {
				$result[$key] = [];
				/**
				 * @var EntityInterface $object
				 */
				foreach ($relatedObjects as $object) {
					if (!$id = $object->getIdValue()) {
						throw new NestedEntityDoesNotExistsException();
					}
					$result[$key][] = $id;
				}
			}
		}
	}

	final public function entityToArray(EntityInterface $entity): array
	{
		$result = [];
		foreach($this->getFields() as $key => $field) {
			if (!$field instanceof RelationInterface) {
				$this->mapBaseTypeToArray($entity, $field, $key, $result);
			} else {
				$this->mapRelationToArray($entity, $field, $key, $result);
			}
		}
		return $result;
	}
}