<?php

namespace Computools\CLightORM\Repository;

use Computools\CLightORM\Entity\EntityInterface;
use Computools\CLightORM\Tools\Pagination;
use LessQL\Database;
use LessQL\Result;
use LessQL\Row;

abstract class AbstractRepository extends RepositoryCore implements RepositoryInterface
{
	abstract function getEntityClass(): string;

	final public function __construct(Database $database)
	{
		$this->database = $database;
		$this->entityClassString = $this->getEntityClass();
		$this->entity = new $this->entityClassString;
		$this->mapper = $this->entity->getMapper();
		$this->table = $this->mapper->getTable();
		$this->mapRelations();
		$this->defineIdentifier();
	}

	protected function mapToEntity(Row $row, Result $query, array $with = []): EntityInterface
	{
		return $this->mapper->arrayToEntity(
			new $this->entityClassString,
			$this->joinRelatedDataToParent($row->getData(), $this->getRelatedData($query, $with))
		);
	}

	protected function mapToEntities(array $rows, Result $query, array $with = []): array
	{
		$result = [];
		/**
		 * @var Row[] $rows
		 */
		foreach($rows as $row) {
			$result[] = $this->mapToEntity($row, $query, $with);
		}
		return $result;
	}

	public function find(int $id, array $with = []): ?EntityInterface
	{
		$query = $this->database->table($this->table)->where($this->mapper->getIdentifier(), $id);
		if (!$result = $query->fetch()) {
			return null;
		}
		return $this->mapToEntity($result, $query, $with);
	}

	public function findFirst(array $with = []): ?EntityInterface
	{
		$query = $this->database->table($this->table)->limit(1);
		if (!$result = $query->fetch()) {
			return null;
		}
		return $this->mapToEntity($result, $query, $with);
	}

	public function findLast(array $with = []): ?EntityInterface
	{
		$query = $this->database->table($this->table)->orderBy($this->mapper->getIdentifier(), 'DESC')->limit(1);
		if (!$result = $query->fetch()) {
			return null;
		}
		return $this->mapToEntity($result, $query, $with);
	}

	public function findOneBy(array $criteria, array $with = []): ?EntityInterface
	{
		$query = $this->database->table($this->table);
		foreach($criteria as $key => $value) {
			$query = $query->where($key, $value);
		}
		$query = $query->limit(1);
		if (!$result = $query->fetch()) {
			return null;
		}
		return $this->mapToEntity($result, $query, $with);
	}

	public function findBy(array $criteria, array $with = [], ?Pagination $pagination = null): array
	{
		$query = $this->database->table($this->table);
		foreach($criteria as $key => $value) {
			$query = $query->where($key, $value);
		}

		if ($pagination) {
			if ($pagination->isPagination()) {
				$query = $query->paged($pagination->getPerPage(), $pagination->getPage());
			} else {
				$query->limit($pagination->getLimit(), $pagination->getOffset());
			}
		}
		return $this->mapToEntities($query->fetchAll(), $query, $with);
	}

	public function remove(EntityInterface $entity): void
	{
		$this->database->table($this->mapper->getTable())->where($this->mapper->getIdentifier(), $entity->getId())->delete();
	}

	public function save(EntityInterface &$entity, array $with = [], $relationExistsCheck = false): EntityInterface
	{
		$data = $this->mapNestedEntitiesToArray(
			$this->mapper->entityToArray($entity),
			$entity->isNew()
		);
		if ($entity->isNew()) {
			unset($data[$entity->getMapper()->getIdentifierEntityField()]);
		}
		$row = $entity->isNew() ? $this->create($data) : $this->update($entity, $data, $relationExistsCheck);

		$query = $this->database->table($this->table)->where($this->mapper->getIdentifier(), $row->getId());

		$entity = $this->mapper->arrayToEntity(
			new $this->entityClassString,
			$this->joinRelatedDataToParent($row->getData(), $this->getRelatedData($query, $with))
		);
		return $entity;
	}
}