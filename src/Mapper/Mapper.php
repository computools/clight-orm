<?php

namespace Computools\CLightORM\Mapper;

use Computools\CLightORM\{
	Entity\EntityInterface, Exception\IdentifierDoesNotExistsException, Exception\InvalidFieldMapException, Exception\NestedEntityDoesNotExistsException, Mapper\Relations\ManyToMany, Mapper\Relations\ManyToOne, Mapper\Relations\OneToOne, Mapper\Relations\RelationInterface, Mapper\Relations\ToOneInterface, Mapper\Types\BooleanType, Mapper\Types\ColumnType, Mapper\Types\CreatedAtType, Mapper\Types\DateTimeType, Mapper\Types\FloatType, Mapper\Types\IdType, Mapper\Types\UpdatedAtType
};

abstract class Mapper implements MapperInterface
{
	abstract function getFields(): array;

	abstract function getTable(): string;

	private function toCamelCase(string $sting, $lowerFirst = false): string
	{
		$fieldParts = array_map(function($item) {
			return ucfirst($item);
		}, explode('_', $sting));
		$camelCase = implode('', $fieldParts);
		return $lowerFirst ? lcfirst($camelCase) : $camelCase;
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

	public function defineSetterName(string $field, bool $addKeyword = true): string
	{
		return sprintf(
			'%s%s',
			$addKeyword ? 'set' : '',
			$this->toCamelCase($field, !$addKeyword)
		);
	}

	public function defineGetterName(string $field, bool $addKeyword = true): string
	{
		return sprintf(
			'%s%s',
			$addKeyword ? 'get' : '',
			$this->toCamelCase($field, !$addKeyword)
		);
	}

	public function mapList(EntityInterface $entity, string $field, array $collection): EntityInterface
	{
		$field = $this->defineSetterName($field);
		$entity->$field($collection);
		return $entity;
	}

	public function mapEntity(EntityInterface $parentEntity, string $field, EntityInterface $childEntity): EntityInterface
	{
		$field = $this->defineSetterName($field);
		$parentEntity->$field($childEntity);
		return $parentEntity;
	}

	final public function arrayToEntity(EntityInterface $entity, array $data = null): ?EntityInterface
	{

			foreach ($entity->getMapper()->getFields() as $key => $field) {
				$entityField = $key;
//				$setter = $this->defineSetterName($key);
//				if (!method_exists($entity, $setter)) {
//					throw new InvalidFieldMapException(get_class($entity));
//				}
				if (!$field instanceof RelationInterface) {
					$key = $field->getColumnName() ? $field->getColumnName() : $key;
					switch (true) {
						case $field instanceof DateTimeType:
							if ($data[$key]) {
								if ($data[$key] instanceof \DateTime) {
									$entity->setField($entityField, $data[$key]);
								} else {
									$entity->setField($entityField, new \DateTime($data[$key]));
								}
							} else {
								$entity->setField($entityField, null);
							}
							break;
						case $field instanceof BooleanType:
							$entity->setField($entityField, (int) $data[$key] ? true : false);
							break;
						case $field instanceof IdType:
							$entity->setField($entityField, $data[$entity->getMapper()->getIdentifier()]);
							break;
						case $field instanceof FloatType:
							$entity->setField($entityField, $data[$key] ? floatval($data[$key]) : null);
							break;
						default:
							$entity->setField($entityField, $data[$key]);
							break;
					}
				} else {
					$relatedEntityClass = $field->getEntityClass();
					if ($field instanceof ManyToOne) {
						if ($data[$key] ?? null) {
							$entity->setField(
								$entityField,
								$this->arrayToEntity(new $relatedEntityClass(), $data[$key])
							);
						}
					} else {
						if ($data[$key] ?? null) {
							$result = [];
							foreach ($data[$key] as $collectionItem) {
								$result[] = $this->arrayToEntity(new $relatedEntityClass(), $collectionItem);
							}
							$entity->setField($entityField, $result);
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
		switch (true) {
			case $columnType instanceof CreatedAtType:
				$result[$key] = $entity->isNew() ? (new \DateTime())->format($columnType->getFormat()) : $entity->getField($entityField)->format($columnType->getFormat());
				break;
			case $columnType instanceof UpdatedAtType:
				$result[$key] = (new \DateTime())->format($columnType->getFormat());
				break;
			case $columnType instanceof BooleanType:
				$result[$key] =  $columnType->asInt() ? ($entity->getField($entityField) ? 1 : 0) : ($entity->getField($entityField));
				break;
			case $columnType instanceof FloatType:
				$result[$key] =  $entity->getField($entityField) ? floatval($entity->getField($entityField)) : null;
				break;
			default:
				$result[$key] =  $entity->getField($entityField);
		}
	}

	private function mapRelationToArray(EntityInterface $entity, RelationInterface $relation, string $key, array &$result): void
	{
		$entityField = $key;
		if ($relation instanceof ToOneInterface) {
			$nestedEntity = $entity->getField($entityField);
			if ($nestedEntity) {
				if (!$id = $entity->getField($entityField)->getId()) {
					throw new NestedEntityDoesNotExistsException();
				}
				$result[$relation->getFieldName()] = $id;
			}
		} else if ($relation instanceof ManyToMany) {
			$relatedObjects = $entity->getField($entityField);

			if (!empty($relatedObjects)) {
				$result[$key] = [];
				foreach ($relatedObjects as $object) {
					if (!$id = $object->getId()) {
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