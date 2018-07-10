<?php

namespace Computools\CLightORM\Repository;

use Computools\CLightORM\{
	Entity\AbstractEntity, Entity\EntityInterface, Exception\EntityDoesNotExistsException, Exception\NestedEntityDoesNotExistsException, Mapper\RelationMap, Mapper\MapperInterface
};

use Computools\CLightORM\Mapper\Relations\{
	ManyToMany, RelationInterface, ToOneInterface
};

use LessQL\Database;
use LessQL\Result;
use LessQL\Row;

abstract class RepositoryCore
{

	use RelatedDataTrait;

	/**
	 * Entity class instance
	 *
	 * @var EntityInterface
	 */
	protected $entity;

	/**
	 * Entity class string
	 *
	 * @var string
	 */
	protected $entityClassString;

	/**
	 * @var Database
	 */
	protected $database;

	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * Mapper object
	 *
	 * @var MapperInterface
	 */
	protected $mapper;

	/**
	 * Database table identifier
	 *
	 * @var string
	 */
	protected $identifier;

	/**
	 * @var RelationMap[]
	 */
	protected $relations = [];

	protected $relatedFields = [];

	/**
	 * This method maps relations defined at mapper to $relations
	 * property as RelationMap objects with Entity's property names as keys
	 */
	final protected function mapRelations(): void
	{
		foreach($this->mapper->getFields() as $name => $type) {
			if ($type instanceof RelationInterface) {
				$this->relations[$name] = new RelationMap($type->getRelatedEntity()->getMapper()->getTable(), $name, $type, $this->mapper->getIdentifier());

				if ($type instanceof ManyToMany) {
					$this->database->setPrimary($type->getTable(), $type->getFields());
				}
				$this->database->setPrimary($type->getRelatedEntity()->getMapper()->getTable(), $type->getRelatedEntity()->getMapper()->getIdentifier());
			}
		}
		$this->database->setPrimary($this->table, $this->mapper->getIdentifier());
	}

	final protected function mapAlienRelations(MapperInterface $mapper): array
	{
		$relations = [];
		foreach($mapper->getFields() as $name => $type) {
			if ($type instanceof RelationInterface) {
				$relations[$name] =
					new RelationMap($type->getRelatedEntity()->getMapper()->getTable(), $name, $type, $mapper->getIdentifier())
				;

				if ($type instanceof ManyToMany) {
					$this->database->setPrimary($type->getTable(), $type->getFields());
				}
				$this->database->setPrimary($type->getRelatedEntity()->getMapper()->getTable(), $type->getRelatedEntity()->getMapper()->getIdentifier());
			}
		}
		$this->database->setPrimary($mapper->getTable(), $mapper->getIdentifier());
		return $relations;
	}

	/**
	 * This method allows you to map nested entities to array
	 *
	 * @param array $data
	 * @param bool $includeManyToMany
	 * @return array
	 */
	final protected function mapNestedEntitiesToArray(array $data, $includeManyToMany = false): array
	{
		foreach($this->relations as $key => $relation) {
			if ($relation->getRelationType() instanceof ToOneInterface) {
				if ($field = $data[$relation->getRelationType()->getFieldName()] ?? null) {
					if (!$relatedField = $this->database->table(
						$relation->getTableName()
					)->where(
						$relation
							->getRelationType()
							->getRelatedEntity()
							->getMapper()
							->getIdentifier(),
						$data[$relation->getRelationType()->getFieldName()])->fetch()
					) {
						throw new NestedEntityDoesNotExistsException();
					}
					unset($data[$relation->getRelationType()->getFieldName()]);
					$data[$relation->getEntityField()] = $relatedField;
				}
			} else if ($relation->getRelationType() instanceof ManyToMany) {
				if (!$includeManyToMany) {
					unset($data[$relation->getEntityField()]);
					continue;
				}
				$relatedName = $relation->getRelationType()->getTable() . 'List';
				if ($relatedIds = $data[$relation->getEntityField()] ?? null) {

					$data[$relatedName] = [];
					unset($data[$relation->getEntityField()]);
					foreach ($relatedIds as $id) {
						if (!$relatedField = $this->database->table($relation->getTableName())
															->where(
																$relation
																	->getRelationType()
																	->getRelatedEntity()
																	->getMapper()
																	->getIdentifier(),
																$id)->fetch()) {
							throw new NestedEntityDoesNotExistsException();
						}
						$data[$relatedName][] = [
							$relation->getTableName() => $relatedField
						];
					}
				}
			}
		}
		return $data;
	}

	/**
	 * Defines table identifier based on Mapper:ID type
	 */
	final protected function defineIdentifier(): void
	{
		$this->identifier = $this->mapper->getIdentifier();
	}

	/**
	 * Gets related data for objects. Requires LessQL\Result object, that filters parent entity and 'with' array
	 *
	 * @param Result $parentEntityQuery
	 * @param array $with
	 * @param array $relations
	 * @return array
	 */
	final protected function getRelatedData(Result $parentEntityQuery, array $with = [], array $relations = null): array
	{
		$relatedData = [];
		$innerWith = [];
		foreach($with as $key => $relatedTable) {
			if (is_array($relatedTable)) {
				$innerWith = $relatedTable;
				$relatedTable = $key;
			}

			if (!$relations) {
				$relations = $this->relations;
			}

			if ($relation = $relations[$relatedTable] ?? null) {
				$query = $this->getRelatedDataResult($parentEntityQuery, $relation, $relatedData);
				$this->getInnerData($innerWith, $query, $relation, $relatedData);
			}
		}

		return $relatedData;
	}

	/**
	 * Joins data to parent, received from getRelatedData() method.
	 *
	 * @param array $parentData
	 * @param array $relatedData
	 * @return array
	 */
	final protected function joinRelatedDataToParent(array $parentData, array $relatedData): array
	{
		foreach($relatedData as $key => $value) {
			$parentData[$key] = $value['data'][$parentData[$value['field']]] ?? null;
			if (is_array($parentData[$key])) {
				if ($value['single']) {
					if (!empty($parentData[$key]['relatedData'])) {
						$parentData[$key] = $this->joinRelatedDataToParent(
							$parentData[$key],
							$parentData[$key]['relatedData']
						);
					}
				} else {
					foreach ($parentData[$key] as $currentKey => $currentData) {
						if (!empty($currentData['relatedData'])) {
							$parentData[$key][$currentKey] = $this->joinRelatedDataToParent(
								$currentData,
								$currentData['relatedData']
							);
						}
					}
				}
			}
		}
		return $parentData;
	}

	/**
	 * Gets relation by field name
	 *
	 * @param string $field
	 * @return RelationMap
	 */
	final protected function getRelationByField(string $field): RelationMap
	{
		foreach($this->relations as $relation) {
			if ($relation->getEntityField() === $field) {
				return $relation;
			}
		}
	}

	/**
	 * Apllies relation changes and save them to DB. Used for many-to-many relations
	 *
	 * @param EntityInterface $entity
	 * @param bool $relationExistsCheck
	 */
	final protected function applyRelationChanges(EntityInterface $entity, bool $relationExistsCheck): void
	{
		foreach($entity->getRelationChanges() as $key => $field) {
			foreach($field as $actionType => $value) {
				$relationMap = $this->getRelationByField($key);
				$table = $relationMap->getRelationType()->getTable() . 'List';
				if ($actionType === AbstractEntity::RELATION_CHANGE_REMOVE) {
					$ids = implode(',', $value);
					$this->database->table($table)->where(
						$relationMap->getRelationType()->getColumnName() . '=' . $entity->getId() . ' AND ' .
						$relationMap->getRelationType()->getReferencedColumnName() . ' IN (' . $ids . ')'
					)->delete();
				} else if ($actionType === AbstractEntity::RELATION_CHANGE_ADD) {
					foreach($value as $relatedId) {
						$criteria = [
							$relationMap->getRelationType()->getColumnName() => $entity->getId(),
							$relationMap->getRelationType()->getReferencedColumnName() => $relatedId
						];
						if (!$relationExistsCheck || !$this->database->$table()->where($criteria)->fetch()) {
							$this->database->table($table)->insert($criteria);
						}
					}
				}
			}
		}
	}

	/**
	 * Database insert operation
	 *
	 * @param array $data
	 * @return Row
	 */
	protected function create(array $data): Row
	{
		$row = $this->database->createRow($this->table, $data);
		$this->database->begin();
		$row->save();
		$this->database->commit();
		return $row;
	}

	/**
	 * Database update operation
	 *
	 * @param EntityInterface $entity
	 * @param array $data
	 * @param bool $relationExistsCheck
	 * @return Row
	 */
	protected function update(EntityInterface $entity, array $data, $relationExistsCheck = false): Row
	{
		if (!$row = $this->database->table($this->table)->where($this->mapper->getIdentifier(), $entity->getId())->fetch()) {
			throw new EntityDoesNotExistsException();
		}
		$this->database->begin();

		$this->applyRelationChanges($entity, $relationExistsCheck);

		$row->update($data);
		$this->database->commit();
		return $row;
	}
}