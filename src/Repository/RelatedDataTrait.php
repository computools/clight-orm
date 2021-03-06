<?php

namespace Computools\CLightORM\Repository;

use Computools\CLightORM\Database\Query\AbstractQuery;
use Computools\CLightORM\Database\Query\Contract\ResultQueryInterface;
use Computools\CLightORM\Database\Query\Contract\SelectQueryInterface;
use Computools\CLightORM\Database\Query\Structure\Join;
use Computools\CLightORM\Database\Query\SelectQuery;
use Computools\CLightORM\Exception\InvalidFieldMapException;
use Computools\CLightORM\Mapper\RelationMap;
use Computools\CLightORM\Mapper\Relations\ManyToMany;
use Computools\CLightORM\Mapper\Relations\OneToMany;
use Computools\CLightORM\Mapper\Relations\ToManyInterface;
use Computools\CLightORM\Mapper\Relations\ToOneInterface;
use Computools\CLightORM\Tools\RelatedData;

trait RelatedDataTrait
{
	private function makeInArgument(array $data, string $identifier): string
	{
		$childIds = array_map(function($item) use ($identifier) {
			if (!array_key_exists($identifier, $item)) {
				throw new InvalidFieldMapException($this->entityClassString, $identifier);
			}
			return $item[$identifier];
		}, $data);

		$childIds = array_filter($childIds, function($item) {
			return !($item === null);
		});

		return implode(', ', $childIds);
	}

	/**
	 * Method calls related data search dependent on relation type
	 *
	 * @param ResultQueryInterface $parentEntityQuery
	 * @param RelationMap $relation
	 * @param array $relatedData
	 * @return SelectQueryInterface
	 */
	private function getRelatedDataResult(ResultQueryInterface $parentEntityQuery, RelationMap $relation, array &$relatedData, RelatedData $relatedDataObject): SelectQueryInterface
	{
		$this->createEmptyRelatedDataItem($relation, $relatedData);

		switch (true) {
			case $relation->getRelationType() instanceof ToOneInterface:
				$query = $this->getManyOrOneToOneRelatedData($parentEntityQuery, $relation, $relatedData, $relatedDataObject);
				break;
			case $relation->getRelationType() instanceof OneToMany:
				$query = $this->getOneToManyRelatedData($parentEntityQuery, $relation, $relatedData, $relatedDataObject);
				break;
			case $relation->getRelationType() instanceof ManyToMany:
				$query = $this->getManyToManyRelatedData($parentEntityQuery, $relation, $relatedData, $relatedDataObject);
				break;
			default:
				throw new InvalidFieldMapException($relation->getRelationType()->getEntityClass());
		}
		return $query;
	}

	/**
	 * Used for OneToMany related data search
	 *
	 * @param ResultQueryInterface $parentEntityQuery
	 * @param RelationMap $relation
	 * @param array $relatedData
	 * @return SelectQueryInterface
	 */
	private function getOneToManyRelatedData(ResultQueryInterface $parentEntityQuery, RelationMap $relation, array &$relatedData, RelatedData $relatedDataObject): SelectQueryInterface
	{
		$table = $relation->getRelationType()->getRelatedEntity()->getTable();

		/**
		 * @var SelectQuery $query
		 */
		$query = $this->orm->createQuery();

		$parentIds = $this->makeInArgument($parentEntityQuery->getResult(), $relation->getParentIdentifier());

		$query
			->from($table)
			->whereExpr(sprintf(
				"%s IN (%s)",
					$relation->getRelationType()->getRelatedTableField(),
				$parentIds
			))
		;

		$params = [];

		foreach ($relatedDataObject->getConditions() as $condition) {
		    $generatedKey = AbstractQuery::generateParamName($condition->getField());
		    $params[$generatedKey] = $condition->getValue();
		    $query->whereExpr($condition->getField() . $condition->getOperator() . ':' . $generatedKey);
        }

        if ($order = $relatedDataObject->getOrder()) {
		    $query->orderBy($order->getField(), $order->getDirection());
        }

        if ($pagination = $relatedDataObject->getPagination()) {
		    list($limit, $offset) = $relatedDataObject->getPagination()->getLimitOffset();
		    $query->limit($limit, $offset);
        }

		if (!empty($parentIds)) {
			$query->execute($params);
		} else {
			$query->setResult([]);
		}

		foreach($query->getResult() as $relationData) {
			$relatedData[$relation->getEntityField()]['data'][$relationData[$relation->getRelationType()->getRelatedTableField()]][] =
				$relationData;
		}

		return $query;
	}

	/**
	 * Used for ManyToOne or OneToOne related data search
	 *
	 * @param ResultQueryInterface $parentEntityQuery
	 * @param RelationMap $relation
	 * @param array $relatedData
	 * @return SelectQueryInterface
	 */
	private function getManyOrOneToOneRelatedData(ResultQueryInterface $parentEntityQuery, RelationMap $relation, array &$relatedData, RelatedData $relatedDataObject): SelectQueryInterface
	{
		$table = $relation->getTableName();

		$parentIds = $this->makeInArgument($parentEntityQuery->getResult(), $relation->getRelationType()->getFieldName());

		/**
		 * @var SelectQuery $query
		 */
		$query = $this->orm->createQuery();
		$query
			->from($table)
			->whereExpr(sprintf(
				"%s IN (%s)",
				$relation->getRelationType()->getRelatedEntity()->getIdentifier(),
				$parentIds
			));

		$params = [];
        foreach ($relatedDataObject->getConditions() as $condition) {
            $generatedKey = AbstractQuery::generateParamName($condition->getField());
            $params[$generatedKey] = $condition->getValue();
            $query->whereExpr($condition->getField() . $condition->getOperator() . ':' . $generatedKey);
        }

		if (!empty($parentIds)) {
			$query->execute($params);
		} else {
			$query->setResult([]);
		}

		foreach($parentEntityQuery->getResult() as $parentResult) {
			if ($query->getResult()) {
				foreach ($query->getResult() as $childResult) {
					if ($parentResult[$relation->getRelationType()->getFieldName()] ==
						$childResult[$relation->getRelationType()->getRelatedEntity()->getIdentifier()]) {
						$relatedData[$relation->getEntityField()]['data'][$childResult[$relation->getRelationType()
																								->getRelatedEntity()
																								->getIdentifier()
						]] = $childResult;
					}
				}
			}
		}
		return $query;
	}

	/**
	 * Used for ManyToMany related data search
	 *
	 * @param ResultQueryInterface $parentEntityQuery
	 * @param RelationMap $relation
	 * @param array $relatedData
	 * @return SelectQueryInterface
	 */
	private function getManyToManyRelatedData(ResultQueryInterface $parentEntityQuery, RelationMap $relation, array &$relatedData, RelatedData $relatedDataObject): SelectQueryInterface
	{
		$table = $relation->getRelationType()->getTable();
		$relatedTableName = $relation->getTableName();

		$parentIds = array_map(function($item) use ($relation) {
			return $item[$relation->getParentIdentifier()];
		}, $parentEntityQuery->getResult());

		//find relation table rows
		/**
		 * @var SelectQueryInterface $query
		 */
		$query = $this->orm->createQuery();

		$relatedTableIdentifier = $relation->getRelationType()->getRelatedEntity()->getIdentifier();

		$on = sprintf(
			'ON %s.%s = %s.%s',
			$relatedTableName,
			$relatedTableIdentifier,
			$table,
			$relation->getRelationType()->getReferencedColumnName()
		);
		$where = sprintf(
			"%s.%s IN (%s)",
			$table,
			$relation->getRelationType()->getColumnName(),
			implode(',', $parentIds)
		);

		$select = sprintf($query->getConcatQuery('%s.%s', ',', 'ids') . ', %s.*',
			$table,
			$relation->getRelationType()->getColumnName(),
			$relatedTableName
		);

		$query
			->select($select)
			->from($table)
			->join(new Join('INNER', $relatedTableName, $on))
			->whereExpr($where)
			->groupBy($relation->getTableName() . '.' . $relatedTableIdentifier);

		$params = [];
        foreach ($relatedDataObject->getConditions() as $condition) {
            $field = $relatedTableName . '.' . $condition->getField();
            $generatedKey = AbstractQuery::generateParamName($field);
            $params[$generatedKey] = $condition->getValue();
            $query->whereExpr($field . $condition->getOperator() . ':' . $generatedKey);
        }

		if ($order = $relatedDataObject->getOrder()) {
		    $query->orderBy($relation->getTableName() . '.' . $order->getField(), $order->getDirection());
        }
        if ($pagination = $relatedDataObject->getPagination()) {
            list($limit, $offset) = $pagination->getLimitOffset();
		    $query->limit($limit, $offset);
        }
		if (!empty($parentIds)) {
			$query->execute($params);
		} else {
			$query->setResult([]);
		}

		foreach ($parentEntityQuery->getResult() as $parentResult) {
			foreach ($query->getResult() as $childResult) {
				if (in_array($parentResult[$relation->getParentIdentifier()], explode(',', $childResult['ids']))) {
					$relatedData[$relation->getEntityField()]['data'][$parentResult[$relation->getParentIdentifier()]][] = $childResult;
				}
			}
		}
		return $query;
	}

	/**
	 * Creates empty related data item
	 *
	 * @param RelationMap $relation
	 * @param array $relatedData
	 */
	private function createEmptyRelatedDataItem(RelationMap $relation, array &$relatedData): void
	{
		if ($relation->getRelationType() instanceof ToOneInterface) {
			$field = $relation->getRelationType()->getFieldName();
		} else {
			$field = $relation->getParentIdentifier();
		}
		$relatedData[$relation->getEntityField()] = [];
		$relatedData[$relation->getEntityField()]['field'] = $field;
		if ($relation->getRelationType() instanceof ToOneInterface) {
			$relatedData[$relation->getEntityField()]['single'] = true;
		} else if ($relation->getRelationType() instanceof ToManyInterface) {
			$relatedData[$relation->getEntityField()]['single'] = false;
		}
		$relatedData[$relation->getEntityField()]['data'] = [];
	}

	/**
	 * Gets related data for specified inner relation
	 *
	 * @param array $innerWith
	 * @param ResultQueryInterface $query
	 * @param RelationMap $relation
	 * @param $relatedData
	 */
	private function getInnerData($innerWith = [], ResultQueryInterface $query, RelationMap $relation, &$relatedData): void
	{
		if ($query && !empty($innerWith)) {
			$innerData = $this->getRelatedData(
				$query,
				$innerWith,
				$this->mapAlienRelations(
					$relation->getRelationType()->getRelatedEntity()
				)
			);

			switch (true) {
				case $relation->getRelationType() instanceof ToManyInterface :
					foreach ($relatedData[$relation->getEntityField()]['data'] as $rootId => $relatedArray) {
						foreach ($relatedArray as $relatedKey => $relatedArrayItem) {
							$relatedData[$relation->getEntityField()]['data'][$rootId][$relatedKey]['relatedData'] = [];
							foreach ($innerData as $innerEntityField => $relationDefinition) {
								$field = $relationDefinition['field'];
								$relatedData[$relation->getEntityField()]['data'][$rootId][$relatedKey]['relatedData'][$innerEntityField] = [];
								$relatedData[$relation->getEntityField()]['data'][$rootId][$relatedKey]['relatedData'][$innerEntityField]['field'] = $field;
								$relatedData[$relation->getEntityField()]['data'][$rootId][$relatedKey]['relatedData'][$innerEntityField]['data'] = [];
								$relatedData[$relation->getEntityField()]['data'][$rootId][$relatedKey]['relatedData'][$innerEntityField]['single'] = $relationDefinition['single'];

								foreach ($relationDefinition['data'] as $key => $relatedList) {
									if ((int) $key === (int) $relatedArrayItem[$field]) {
										$relatedData[$relation->getEntityField()]['data'][$rootId][$relatedKey]['relatedData'][$innerEntityField]['data'][$key] = $relatedList;
									}
								}
							}

						}
					}
					break;
				case $relation->getRelationType() instanceof ToOneInterface :
					foreach ($relatedData[$relation->getEntityField()]['data'] as $relatedKey => $relatedArrayItem) {
						$relatedData[$relation->getEntityField()]['data'][$relatedKey]['relatedData'] = [];
						foreach ($innerData as $innerEntityField => $relationDefinition) {
							$field = $relationDefinition['field'];
							$relatedData[$relation->getEntityField()]['data'][$relatedKey]['relatedData'][$innerEntityField] = [];
							$relatedData[$relation->getEntityField()]['data'][$relatedKey]['relatedData'][$innerEntityField]['field'] = $field;
							$relatedData[$relation->getEntityField()]['data'][$relatedKey]['relatedData'][$innerEntityField]['data'] = [];
							$relatedData[$relation->getEntityField()]['data'][$relatedKey]['relatedData'][$innerEntityField]['single'] = $relationDefinition['single'];

							foreach ($relationDefinition['data'] as $key => $relatedList) {
								if ((int) $key === (int) $relatedArrayItem[$field]) {
									$relatedData[$relation->getEntityField()]['data'][$relatedKey]['relatedData'][$innerEntityField]['data'][$key] = $relatedList;
								}
							}
						}
					}
					break;
				default:
					throw new InvalidFieldMapException($relation->getRelationType()->getEntityClass());
			}
		}
	}
}
