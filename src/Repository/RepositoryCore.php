<?php

namespace Computools\CLightORM\Repository;

use Computools\CLightORM\{
    Cache\CacheInterface, CLightORM, Database\Query\Contract\ResultQueryInterface, Database\Query\Contract\SelectQueryInterface, Entity\EntityInterface, Exception\NestedEntityDoesNotExistsException, Mapper\FieldMapStorage, Mapper\RelationMap, Mapper\MapperInterface
};

use Computools\CLightORM\Mapper\Relations\{
    ManyToMany, RelationChangesList, RelationInterface, ToOneInterface
};

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
	 * @var CLightORM
	 */
	protected $orm;

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
	 * @var CacheInterface
	 */
	protected $cache;

	protected $cachedEntities = [];

	/**
	 * This method maps relations defined at mapper to $relations
	 * property as RelationMap objects with Entity's property names as keys
	 */
	final protected function mapRelations(): void
	{
		foreach(FieldMapStorage::getFields($this->entity) as $name => $type) {
			if ($type instanceof RelationInterface) {
				$this->relations[$name] = new RelationMap(
					$type->getRelatedEntity()->getTable(), $name, $type, $this->entity->getIdentifier()
				);
			}
		}
	}

	final protected function mapAlienRelations(EntityInterface $entity): array
	{
		$relations = [];
		foreach(FieldMapStorage::getFields($entity) as $name => $type) {
			if ($type instanceof RelationInterface) {
				$relations[$name] =
					new RelationMap($type->getRelatedEntity()->getTable(), $name, $type, $type->getRelatedEntity()->getIdentifier())
				;
			}
		}
		return $relations;
	}

	/**
	 * This method allows you to map nested entities to array
	 *
     * @param EntityInterface $rootEntity
	 * @param array $data
	 * @param bool $includeManyToMany
	 * @return array
	 */
	final protected function mapNestedEntitiesToArray(EntityInterface $rootEntity, array $data, $includeManyToMany = false): array
	{
		foreach ($this->relations as $key => $relation) {
			if ($relation->getRelationType() instanceof ToOneInterface) {
				$identifier = $relation
					->getRelationType()
					->getRelatedEntity()
					->getIdentifier();
				$identifierValue = $data[$relation->getRelationType()->getFieldName()] ?? null;

				if (!$identifierValue) {
				    if ($tableField = $rootEntity->getRelationChangesList()->getToOneByKey($key)) {
				        $data[$tableField] = null;
                    }
					continue;
				}

				if (!isset($this->cachedEntities[$relation->getTableName()][$identifierValue])) {
					if ($field = $data[$relation->getRelationType()->getFieldName()] ?? null) {
						$query = $this->orm->createQuery();
						$query->from($relation->getTableName())->where($identifier, $identifierValue)->execute();

						if (!$relatedField = $query->getFirst()) {
							throw new NestedEntityDoesNotExistsException();
						}
						$this->cachedEntities[$relation->getTableName()][$identifierValue] = $query->getFirst();
					}
				}
			} else if ($relation->getRelationType() instanceof ManyToMany) {
				if (!$includeManyToMany) {
					unset($data[$relation->getEntityField()]);
					continue;
				}
				if ($relatedIds = $data[$relation->getEntityField()] ?? null) {
					unset($data[$relation->getEntityField()]);

					$query = $this->orm->createQuery();
					$query
						->from($relation->getTableName())
						->whereExpr($relation
							->getRelationType()
							->getRelatedEntity()
							->getIdentifier() . ' IN (' . implode(',', $relatedIds) . ')')
						->execute();

					if (count($relatedIds) > count($query->getResult())) {
						throw new NestedEntityDoesNotExistsException();
					}
				}
			}
		}
		return $data;
	}

	/**
	 * Defines table identifier based on IdType field
	 */
	final protected function defineIdentifier(): void
	{
		$this->identifier = $this->entity->getIdentifier();
	}

	/**
	 * Gets related data for objects. Requires SelectQueryInterface, that filters parent entity and 'with' array
	 *
	 * @param SelectQueryInterface $parentEntityQuery
	 * @param array $with
	 * @param array $relations
	 * @return array
	 */
	final protected function getRelatedData(ResultQueryInterface $parentEntityQuery, array $with = [], array $relations = null): array
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
	final protected function applyRelationChanges(EntityInterface $entity, bool $relationExistsCheck, $identifier = null): void
	{
		$id = $identifier ? $identifier : $entity->getIdValue();
		foreach($entity->getRelationChangesList()->getToManyChanges() as $key => $field) {
			foreach($field as $actionType => $value) {
				$relationMap = $this->getRelationByField($key);
				$table = $relationMap->getRelationType()->getTable();
                $ids = implode(',', $value);
				if ($actionType === RelationChangesList::RELATION_CHANGE_REMOVE) {
					$this->orm->createDeleteQuery()
						->from($table)
						->where($relationMap->getRelationType()->getColumnName(), $id)
						->whereExpr($relationMap->getRelationType()->getReferencedColumnName() . ' IN (' . $ids . ')')
						->execute();
				} else if ($actionType === RelationChangesList::RELATION_CHANGE_ADD) {
				    $query = $this->orm->createQuery();
				    $query
                        ->select($relationMap->getRelationType()->getReferencedColumnName())
                        ->from($table)
                        ->where($relationMap->getRelationType()->getColumnName(), $id)
                        ->whereExpr($relationMap->getRelationType()->getReferencedColumnName() . ' IN (' . $ids . ')')
                        ->execute();
                    $existedIds = $query->getPick($relationMap->getRelationType()->getReferencedColumnName());

					foreach($value as $relatedId) {
                        if (!$relationExistsCheck || !in_array($relatedId, $existedIds)) {
                            $criteria = [
                                $relationMap->getRelationType()->getColumnName() => $id,
                                $relationMap->getRelationType()->getReferencedColumnName() => $relatedId
                            ];

                            $this->orm->createInsertQuery()
                                    ->into($table)
                                    ->values($criteria)
                                    ->execute();
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
	 * @return SelectQueryInterface
	 */
	protected function create(array $data): SelectQueryInterface
	{
		$query = $this->orm->createInsertQuery();

		$query
			->into($this->table)
			->values($data);
		$id = $query->execute()->getLastId();

		return $this->orm->createQuery()
						 ->from($this->table)
						 ->where($this->entity->getIdentifier(), $id)
						 ->execute();
	}

	/**
	 * Database update operation
	 *
	 * @param EntityInterface $entity
	 * @param array $data
	 * @param bool $relationExistsCheck
	 * @return SelectQueryInterface
	 */
	protected function update(EntityInterface $entity, array $data, $relationExistsCheck = false): SelectQueryInterface
	{
		unset($data[$this->entity->getIdentifier()]);
		$query = $this->orm->createUpdateQuery();
		$query->table($this->table)->values($data)->where($this->entity->getIdentifier(), $entity->getIdValue())->execute();

		$query = $this->orm->createQuery();
		$query->from($this->table)->where($this->entity->getIdentifier(), $entity->getIdValue());

		$query->execute();
		return $query;
	}
}
