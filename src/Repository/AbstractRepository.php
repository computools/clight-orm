<?php

namespace Computools\CLightORM\Repository;

use Computools\CLightORM\Cache\CacheInterface;
use Computools\CLightORM\CLightORM;
use Computools\CLightORM\Database\Query\Contract\ResultQueryInterface;
use Computools\CLightORM\Entity\EntityInterface;
use Computools\CLightORM\Exception\WrongRepositoryCalledException;
use Computools\CLightORM\Mapper\Mapper;
use Computools\CLightORM\Tools\Order;
use Computools\CLightORM\Tools\Pagination;

abstract class AbstractRepository extends RepositoryCore implements RepositoryInterface
{
	use CacheTrait;

	abstract function getEntityClass(): string;

	final public function __construct(CLightORM $orm, ?CacheInterface $cache = null)
	{
		$this->orm = $orm;
		$this->cache = $cache;
		$this->entityClassString = $this->getEntityClass();
		$this->entity = new $this->entityClassString;
		$this->mapper = new Mapper();
		$this->table = $this->entity->getTable();
		$this->mapRelations();
		$this->defineIdentifier();
	}

	protected function mergeCriteria(...$criteria)
	{
		return $criteria;
	}

	protected function mapToEntity(ResultQueryInterface $query, array $with = [], $data = null, array $relatedData = null): EntityInterface
	{
		return $this->mapper->arrayToEntity(
			$this->createEntityClass($this->entityClassString)->setOriginalData($data ? $data : $query->getFirst()),
			$this->joinRelatedDataToParent($data ? $data : $query->getFirst(), $relatedData ? $relatedData : $this->getRelatedData($query, $with))
		);
	}

	protected function mapToEntities(ResultQueryInterface $query, array $with = []): array
	{
		$result = [];
		$relatedData = $this->getRelatedData($query, $with);
		$rows = $query->getResult();

		foreach($rows as $row) {
			$result[] = $this->mapToEntity($query, $with, $row, $relatedData);
		}
		return $result;
	}

	public function find(int $id, array $with = [], int $expiration = 0): ?EntityInterface
	{
		if ($result = $this->getFromCache($this->mergeCriteria($id, $with), $expiration)) {
			return $result;
		}

		$query = $this->orm->createQuery();
		$query
			->from($this->table)
			->where($this->entity->getIdentifier(), $id)
			->execute();
		if (!$query->getFirst()) {
			return null;
		}
		$result = $this->mapToEntity($query, $with);
		$this->putToCache($result, $this->mergeCriteria($id, $with), $expiration);
		return $result;
	}

	public function findFirst(array $with = []): ?EntityInterface
	{
		$query = $this->orm->createQuery();
		$query
			->select('*')
			->from($this->table)
            ->orderBy($this->entity->getIdentifier())
			->limit(1);
		$query->execute();

		return $this->mapToEntity($query, $with);
	}

	public function findLast(array $with = []): ?EntityInterface
	{
		$query = $this->orm->createQuery();
		$query
			->select('*')
			->from($this->table)
			->orderBy($this->entity->getIdentifier(), 'DESC')
			->limit(1)
			->execute();

		if (!$result = $query->getFirst()) {
			return null;
		}
		return $this->mapToEntity($query, $with);
	}

	public function findOneBy(array $criteria, ?Order $order = null, array $with = [], int $expiration = 0): ?EntityInterface
	{
		if ($result = $this->getFromCache($this->mergeCriteria($criteria, $with, $order ? $order->toArray() : []), $expiration)) {
			return $result;
		}

		$query = $this->orm->createQuery();
		$query->from($this->table);

		foreach ($criteria as $key => $value) {
			$query->where($key , $value);
		}
		$query->limit(1);
		$query->execute();
		if (!$result = $query->getFirst()) {
			return null;
		}
		$result = $this->mapToEntity($query, $with);
		$this->putToCache($result, $this->mergeCriteria($criteria, $with, $order ? $order->toArray() : []), $expiration);
		return $result;
	}

	public function findBy(array $criteria, ?Order $order = null, array $with = [], ?Pagination $pagination = null, int $expiration = 0): array
	{
		if ($result = $this->getFromCache(
			$this->mergeCriteria($criteria, $with, $order ? $order->toArray() : [], $pagination ? $pagination->toArray(): null), $expiration)
		) {
			return $result;
		}

		$query = $this->orm->createQuery();
		$query->from($this->table);

		foreach ($criteria as $key => $value) {
			$query->where($key, $value);
		}

		if ($order) {
			$query->orderBy($order->getField(), $order->getDirection());
		}

		if ($pagination) {
			if ($pagination->isPagination()) {
				$query->limit($pagination->getPerPage(), ($pagination->getPage() - 1) * $pagination->getPerPage());
			} else {
				$query->limit($pagination->getLimit(), $pagination->getOffset());
			}
		}

		$query->execute();
		$result = $this->mapToEntities($query, $with);
		$this->putToCache($result, $this->mergeCriteria($criteria, $with, $order ? $order->toArray() : []), $expiration);
		return $result;
	}

	public function remove(EntityInterface $entity): void
	{
		if (!$entity instanceof $this->entityClassString) {
			throw new WrongRepositoryCalledException();
		}
		$this
			->orm
			->createDeleteQuery()
			->from($this->entity->getTable())
			->where(
				$this->entity->getIdentifier(),
				$entity->getIdValue()
			)->execute();
	}

	public function save(EntityInterface &$entity, array $with = [], $relationExistsCheck = false): EntityInterface
	{
		if (!$entity instanceof $this->entityClassString) {
			throw new WrongRepositoryCalledException();
		}
		$data = $this->mapNestedEntitiesToArray(
		    $entity,
			$this->mapper->entityToArray($entity),
			$entity->isNew()
		);
		if ($entity->isNew()) {
			unset($data[$entity->getIdentifierEntityField()]);
		}
		$query = $entity->isNew() ? $this->create($data) : $this->update($entity, $data, $relationExistsCheck);

		$id = $this->entity->getIdentifierFromArray($query->getFirst());

		$this->applyRelationChanges($entity, $relationExistsCheck, $id);

		$entity = $this->mapper->arrayToEntity(
			new $this->entityClassString,
			$this->joinRelatedDataToParent($query->getFirst(), $this->getRelatedData($query, $with))
		);
		return $entity;
	}

	private function createEntityClass(string $class): EntityInterface
    {
        return new $class;
    }
}