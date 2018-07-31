<?php

namespace Computools\CLightORM\Repository;

use Computools\CLightORM\Database\Query\Join;
use Computools\CLightORM\Database\Query\MySQLQuery;
use Computools\CLightORM\Database\Query\Query;
use Computools\CLightORM\Exception\InvalidFieldMapException;
use Computools\CLightORM\Mapper\RelationMap;
use Computools\CLightORM\Mapper\Relations\ManyToMany;
use Computools\CLightORM\Mapper\Relations\OneToMany;
use Computools\CLightORM\Mapper\Relations\ToManyInterface;
use Computools\CLightORM\Mapper\Relations\ToOneInterface;
use LessQL\Result;

trait RelatedDataTrait
{
	/**
	 * Method calls related data search dependent on relation type
	 *
	 * @param Result $parentEntityQuery
	 * @param RelationMap $relation
	 * @param array $relatedData
	 * @return Result
	 */
	private function getRelatedDataResult(Query $parentEntityQuery, RelationMap $relation, array &$relatedData): Query
	{
		$this->createEmptyRelatedDataItem($relation, $relatedData);

		switch (true) {
			case $relation->getRelationType() instanceof ToOneInterface:
				$query = $this->getManyOrOneToOneRelatedData($parentEntityQuery, $relation, $relatedData);
				break;
			case $relation->getRelationType() instanceof OneToMany:
				$query = $this->getOneToManyRelatedData($parentEntityQuery, $relation, $relatedData);
				break;
			case $relation->getRelationType() instanceof ManyToMany:
				$query = $this->getManyToManyRelatedData($parentEntityQuery, $relation, $relatedData);
				break;
			default:
				throw new InvalidFieldMapException($relation->getRelationType()->getEntityClass());
		}
		return $query;
	}

	/**
	 * Used for OneToMany related data search
	 *
	 * @param Result $parentEntityQuery
	 * @param RelationMap $relation
	 * @param array $relatedData
	 * @return Result
	 */
	private function getOneToManyRelatedData(Query $parentEntityQuery, RelationMap $relation, array &$relatedData): Query
	{
		$table = $relation->getRelationType()->getRelatedEntity()->getMapper()->getTable();

		/**
		 * @var Query $query
		 */
		$query = $this->orm->createQuery();

		$parentIds = implode(',', array_map(function($item) use ($relation) {
				return $item[$relation->getParentIdentifier()];
			}, $parentEntityQuery->getResult())
		);

		$query
			->select('*')
			->from($table)
			->where(sprintf(
				"%s IN (%s)",
					$relation->getRelationType()->getRelatedTableField(),
				$parentIds
			))
		;

		$query->execute([
			'ids' => $parentIds
		]);

		foreach($query->getResult() as $relationData) {
			$relatedData[$relation->getEntityField()]['data'][$relationData[$relation->getRelationType()->getRelatedTableField()]][] =
				$relationData;
		}

		return $query;
	}

	/**
	 * Used for ManyToOne or OneToOne related data search
	 *
	 * @param Result $parentEntityQuery
	 * @param RelationMap $relation
	 * @param array $relatedData
	 * @return Result
	 */
	private function getManyOrOneToOneRelatedData(Query $parentEntityQuery, RelationMap $relation, array &$relatedData): Query
	{
		$table = $relation->getTableName();

		$childIds = implode(',', array_map(function($item) use ($relation) {
				return $item[$relation->getRelationType()->getFieldName()];
			}, $parentEntityQuery->getResult())
		);

		/**
		 * @var Query $query
		 */
		$query = $this->orm->createQuery();
		$query
			->select('*')
			->from($table)
			->where(sprintf(
				"%s IN (%s)",
				$relation->getRelationType()->getRelatedEntity()->getMapper()->getIdentifier(),
				$childIds
			));

		$query->execute();

		foreach($parentEntityQuery->getResult() as $parentResult) {
			foreach($query->getResult() as $childResult) {
				if ($parentResult[$relation->getRelationType()->getFieldName()] == $childResult[$relation->getRelationType()->getRelatedEntity()->getMapper()->getIdentifier()]) {
					$relatedData[$relation->getEntityField()]['data'][$childResult[$relation->getRelationType()->getRelatedEntity()->getMapper()->getIdentifier()]] = $childResult;
				}
			}
		}
		return $query;
	}

	/**
	 * Used for ManyToMany related data search
	 *
	 * @param Result $parentEntityQuery
	 * @param RelationMap $relation
	 * @param array $relatedData
	 * @return Result
	 */
	private function getManyToManyRelatedData(Query $parentEntityQuery, RelationMap $relation, array &$relatedData): Result
	{
		$table = $relation->getRelationType()->getTable();
		$relatedTableName = $relation->getTableName();

		$parentIds = array_map(function($item) use ($relation) {
			return $item[$relation->getParentIdentifier()];
		}, $parentEntityQuery->getResult());
		//find relation table rows

		/**
		 * @var Query $query
		 */
		$query = $this->orm->createQuery();

		$relatedTableIdentifier = $relation->getRelationType()->getRelatedEntity()->getMapper()->getIdentifier();

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

		$select = sprintf('GROUP_CONCAT(%s.%s) AS ids, %s.*',
			$table,
			$relation->getRelationType()->getColumnName(),
			$relatedTableName
		);

		$query
			->select($select)
			->from($table)
			->join(new Join('INNER', $relatedTableName, $on))
			->where($where)
			->groupBy($relation->getTableName() . '.' . $relatedTableIdentifier);

		$query->execute();


		foreach ($parentEntityQuery->getResult() as $parentResult) {
			foreach ($query->getResult() as $childResult) {
				if (in_array($parentResult[$relation->getParentIdentifier()], explode(',', $childResult['ids']))) {
					//add
					//$relatedData[$relation->getEntityField()]['data'][$childResult[$relatedTableIdentifier]][]
				}
				$b = 1;
			}
		}
//		foreach($relationsData as $relationData) {
//			$relatedData[$relation->getEntityField()]['data'][$relationData[$relation->getRelationType()->getColumnName()]][] =
//				$relatedTableData[$relationData[$relation->getRelationType()->getReferencedColumnName()]];
//		}
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
	 * @param Result $query
	 * @param RelationMap $relation
	 * @param $relatedData
	 */
	private function getInnerData(array $innerWith = [], Query $query, RelationMap $relation, &$relatedData)
	{
		if ($query && !empty($innerWith)) {
			$innerData = $this->getRelatedData(
				$query,
				$innerWith,
				$this->mapAlienRelations(
					$relation->getRelationType()->getRelatedEntity()->getMapper()
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
