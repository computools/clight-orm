<?php

namespace Computools\CLightORM\Mapper;

use Computools\CLightORM\{
	Entity\EntityInterface, Exception\IdentifierDoesNotExistsException, Exception\InvalidFieldMapException, Exception\NestedEntityDoesNotExistsException, Mapper\Relations\ManyToMany, Mapper\Relations\ManyToOne, Mapper\Relations\OneToOne, Mapper\Relations\RelationInterface, Mapper\Relations\ToOneInterface, Mapper\Types\BooleanType, Mapper\Types\ColumnType, Mapper\Types\CreatedAtType, Mapper\Types\DateTimeType, Mapper\Types\FloatType, Mapper\Types\IdType, Mapper\Types\UpdatedAtType
};

abstract class Mapper implements MapperInterface
{
	abstract function getFields(): array;

	abstract function getTable(): string;

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

	public function defineMethodNames(string $field): array
	{
		return [
			$this->defineSetterName($field),
			$this->defineGetterName($field)
		];
	}

	public function defineSetterName(string $field): string
	{
		$fieldParts = array_map(function($item) {
			return ucfirst($item);
		}, explode('_', $field));
		return sprintf(
			'set%s',
			implode('', $fieldParts)
		);
	}

	public function defineGetterName(string $field): string
	{
		$fieldParts = array_map(function($item) {
			return ucfirst($item);
		}, explode('_', $field));
		return sprintf(
			'get%s',
			implode('', $fieldParts)
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
		try {
			foreach ($entity->getMapper()->getFields() as $key => $field) {
				$setter = $this->defineSetterName($key);
				if (!method_exists($entity, $setter)) {
					throw new InvalidFieldMapException(get_class($entity));
				}
				if (!$field instanceof RelationInterface) {
					$key = $field->getColumnName() ? $field->getColumnName() : $key;
					switch (true) {
						case $field instanceof DateTimeType:
							if ($data[$key]) {
								if ($data[$key] instanceof \DateTime) {
									$entity->$setter($data[$key]);
								} else {
									$entity->$setter(new \DateTime($data[$key]));
								}
							} else {
								$entity->$setter(null);
							}
							break;
						case $field instanceof BooleanType:
							$entity->$setter((int) $data[$key] ? true : false);
							break;
						case $field instanceof IdType:
							$entity->$setter($data[$entity->getMapper()->getIdentifier()]);
							break;
						case $field instanceof FloatType:
							$entity->$setter($data[$key] ? floatval($data[$key]) : null);
							break;
						default:
							$entity->$setter($data[$key]);
							break;
					}
				} else {
					$relatedEntityClass = $field->getEntityClass();
					if ($field instanceof ManyToOne) {
						if ($data[$key] ?? null) {
							$entity->$setter(
								$this->arrayToEntity(new $relatedEntityClass(), $data[$key])
							);
						}
					} else {
						if ($data[$key] ?? null) {
							$result = [];
							foreach ($data[$key] as $collectionItem) {
								$result[] = $this->arrayToEntity(new $relatedEntityClass(), $collectionItem);
							}
							$entity->$setter($result);
						}
					}
				}
			}
		} catch (\Exception $exception) {
			throw new InvalidFieldMapException(get_class($entity));
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
		$getter = $this->defineGetterName($key);
		$key = $columnType->getColumnName() ? $columnType->getColumnName() : $key;
		switch (true) {
			case $columnType instanceof CreatedAtType:
				$result[$key] = $entity->isNew() ? (new \DateTime())->format($columnType->getFormat()) : $entity->$getter()->format($columnType->getFormat());
				break;
			case $columnType instanceof UpdatedAtType:
				$result[$key] = (new \DateTime())->format($columnType->getFormat());
				break;
			case $columnType instanceof BooleanType:
				$result[$key] =  $columnType->asInt() ? ($entity->$getter() ? 1 : 0) : ($entity->$getter());
				break;
			case $columnType instanceof FloatType:
				$result[$key] =  $entity->$getter() ? floatval($entity->$getter()) : null;
				break;
			default:
				$result[$key] =  $entity->$getter();
		}
	}

	private function mapRelationToArray(EntityInterface $entity, RelationInterface $relation, string $key, array &$result): void
	{
		if ($relation instanceof ToOneInterface) {
			$getter = $this->defineGetterName($key);
			$nestedEntity = $entity->$getter();
			if ($nestedEntity) {
				if (!$id = $entity->$getter()->getId()) {
					throw new NestedEntityDoesNotExistsException();
				}
				$result[$relation->getFieldName()] = $id;
			}
		} else if ($relation instanceof ManyToMany) {
			$getter = $this->defineGetterName($key);
			$relatedObjects = $entity->$getter();

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