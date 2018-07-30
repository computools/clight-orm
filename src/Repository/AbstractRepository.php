<?php

namespace Computools\CLightORM\Repository;

use Computools\CLightORM\Cache\CacheInterface;
use Computools\CLightORM\Database\Query\Query;
use Computools\CLightORM\Entity\EntityInterface;
use Computools\CLightORM\Tools\Order;
use Computools\CLightORM\Tools\Pagination;
use Computools\CLightORM\Database\Database;
use LessQL\Result;
use LessQL\Row;

abstract class AbstractRepository extends RepositoryCore implements RepositoryInterface
{
	use CacheTrait;

	abstract function getEntityClass(): string;

	final public function __construct(Database $database, ?CacheInterface $cache = null)
	{
		$this->database = $database;
		$this->cache = $cache;
		$this->entityClassString = $this->getEntityClass();
		$this->entity = new $this->entityClassString;
		$this->mapper = $this->entity->getMapper();
		$this->table = $this->mapper->getTable();
		$this->mapRelations();
		$this->defineIdentifier();
	}

	protected function mergeCriteria(...$criteria)
	{
		return $criteria;
	}

	protected function mapToEntity(array $data, $query, array $with = []): EntityInterface
	{
		return $this->mapper->arrayToEntity(
			new $this->entityClassString,
			$this->joinRelatedDataToParent($data, $this->getRelatedData($query, $with))
		);
	}

	protected function mapToEntities(array $rows, Query $query, array $with = []): array
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

	public function find(int $id, array $with = [], $expiration = 0): ?EntityInterface
	{
		if ($result = $this->getFromCache($this->mergeCriteria($id, $with), $expiration)) {
			return $result;
		}

		$query = $this->database->createQuery();
		$query
			->select('*')
			->from($this->table)
			->where($this->mapper->getIdentifier() . '= :id');
		$result = $this->database->executeQuery($query, ['id' => $id]);


//		$query = $this->database->table($this->table)->where($this->mapper->getIdentifier(), $id);
//		if (!$result = $query->fetch()) {
//			return null;
//		}
		$result = $this->mapToEntity($result[0], $query, $with);
		$this->putToCache($result, $this->mergeCriteria($id, $with), $expiration);
		return $result;
	}

	public function findFirst(array $with = []): ?EntityInterface
	{
		$query = $this->database->createQuery();
		$query
			->select('*')
			->from($this->table)
			->limit(1);
		$this->database->executeQuery($query);

		return $this->mapToEntity($query->getFirst(), $query, $with);
	}

	public function findLast(array $with = []): ?EntityInterface
	{
		$query = $this->database->table($this->table)->orderBy($this->mapper->getIdentifier(), 'DESC')->limit(1);
		if (!$result = $query->fetch()) {
			return null;
		}
		return $this->mapToEntity($result, $query, $with);
	}

	public function findOneBy(array $criteria, ?Order $order = null, array $with = [], $expiration = 0): ?EntityInterface
	{
		if ($result = $this->getFromCache($this->mergeCriteria($criteria, $with, $order ? $order->toArray() : []), $expiration)) {
			return $result;
		}
		$query = $this->database->table($this->table);
		foreach ($criteria as $key => $value) {
			$query = $query->where($key, $value);
		}
		$query = $query->limit(1);
		if (!$result = $query->fetch()) {
			return null;
		}
		$result = $this->mapToEntity($result, $query, $with);
		$this->putToCache($result, $this->mergeCriteria($criteria, $with, $order ? $order->toArray() : []), $expiration);
		return $result;
	}

	public function findBy(array $criteria, ?Order $order = null, array $with = [], ?Pagination $pagination = null, $expiration = 0): array
	{
		if ($result = $this->getFromCache(
			$this->mergeCriteria($criteria, $with, $order ? $order->toArray() : [], $pagination ? $pagination->toArray(): null), $expiration)
		) {
			return $result;
		}

		$query = $this->database->createQuery();
		$query->select('*');
		$query->from($this->table);

//		$query = $this->database->table($this->table);
		$params = [];
		foreach ($criteria as $key => $value) {
//			$query = $query->where($key, $value);
			$query->where($key . ' = ' . $value);
			$params[$key] = $value;
		}

		if ($order) {
			$query->orderBy($order->getField(), $order->getDirection());
//			$query = $query->orderBy($order->getField(), $order->getDirection());
		}

//		if ($pagination) {
//			if ($pagination->isPagination()) {
//				$query = $query->paged($pagination->getPerPage(), $pagination->getPage());
//			} else {
//				$query->limit($pagination->getLimit(), $pagination->getOffset());
//			}
//		}
		$result = $this->database->executeQuery($query, $params);
		$result = $this->mapToEntities($result, $query, $with);
		$this->putToCache($result, $this->mergeCriteria($criteria, $with, $order ? $order->toArray() : []), $expiration);
		return $result;
	}

	public function remove(EntityInterface $entity): void
	{
		$this->database->table($this->mapper->getTable())->where($this->mapper->getIdentifier(), $entity->getIdValue())->delete();
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