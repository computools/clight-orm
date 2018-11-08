<?php

namespace Computools\CLightORM\Mapper;

use Computools\CLightORM\{
    Entity\EntityInterface, Exception\IdentifierDoesNotExistsException, Exception\InvalidFieldMapException, Exception\NestedEntityDoesNotExistsException, Helper\ReflectionHelper, Helper\StringHelper
};
use Computools\CLightORM\Mapper\Relations\{
	ManyToMany, ManyToOne, RelationInterface, ToManyInterface, ToOneInterface
};
use Computools\CLightORM\Mapper\Types\{
	BooleanType,
	ColumnType,
	CreatedAtType,
	DateTimeType,
	FloatType,
	IdType,
	UpdatedAtType
};

abstract class Mapper implements MapperInterface
{
	abstract function getFields(): array;

	abstract function getTable(): string;

	public function getIdentifierFromArray(array $data): ?int
	{
		return $data[$this->getIdentifier()] ?? null;
	}

	public function getIdentifier(): string
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

	public function getIdentifierEntityField(): string
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
				switch (true) {
					case $field instanceof DateTimeType:
						if ($data[$key]) {
							if ($data[$key] instanceof \DateTime) {
                                ReflectionHelper::setEntityProperty($entity, $entityField, $data[$key]);
							} else {
                                ReflectionHelper::setEntityProperty($entity, $entityField, new \DateTime($data[$key]));
							}
						} else {
                            ReflectionHelper::setEntityProperty($entity, $entityField, null);
						}
						break;
					case $field instanceof BooleanType:
                        ReflectionHelper::setEntityProperty($entity, $entityField, (int) $data[$key] ? true : false);
						break;
					case $field instanceof IdType:
                        ReflectionHelper::setEntityProperty($entity, $entityField, $data[$entity->getMapper()->getIdentifier()]);
						break;
					case $field instanceof FloatType:
                        ReflectionHelper::setEntityProperty($entity, $entityField, $data[$key] ? floatval($data[$key]) : null);
						break;
					default:
                        ReflectionHelper::setEntityProperty($entity, $entityField, $data[$key]);
						break;
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

	public function cleanField(EntityInterface $entity, string $field): EntityInterface
	{
		$setter = $this->defineSetterName($field);
		$entity->$setter(null);
		return $entity;
	}

	private function mapBaseTypeToArray(EntityInterface $entity, ColumnType $columnType, string $key, array &$result): void
	{
		$entityField = $key;
		$key = $columnType->getColumnName() ? $columnType->getColumnName() : $key;

		$entityValue = ReflectionHelper::getEntityProperty($entity, $entityField);
		switch (true) {
			case $columnType instanceof CreatedAtType:
				$result[$key] = $entity->isNew()
                    ? (new \DateTime())->format($columnType->getFormat())
                    : (
                        $entityValue
                            ? $entityValue->format($columnType->getFormat())
                            : null
                    );
				break;
			case $columnType instanceof UpdatedAtType:
				$result[$key] = (new \DateTime())->format($columnType->getFormat());
				break;
			case $columnType instanceof BooleanType:
				$result[$key] =  $columnType->asInt() ? ($entityValue ? 1 : 0) : ($entityValue);
				break;
			case $columnType instanceof FloatType:
				$result[$key] =  $entityValue ? floatval($entityValue) : null;
				break;
			case $columnType instanceof DateTimeType:
				$result[$key] = ($entityValue) ? $entityValue->format($columnType->getFormat()) : null;
				break;
			default:
				$result[$key] =  $entityValue;
		}
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