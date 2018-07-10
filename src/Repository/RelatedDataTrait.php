<?php

namespace Computools\CLightORM\Repository;

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
	private function getRelatedDataResult(Result $parentEntityQuery, RelationMap $relation, array &$relatedData): Result
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
	private function getOneToManyRelatedData(Result $parentEntityQuery, RelationMap $relation, array &$relatedData): Result
	{
		$table = $relation->getRelationType()->getRelatedEntity()->getMapper()->getTable() . 'List';

		$query = $parentEntityQuery->$table()->via($relation->getRelationType()->getRelatedTableField());
		$relationsData = array_map(function($item) {
			return $item->getData();
		}, $query->fetchAll());

		foreach($relationsData as $relationData) {
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
	private function getManyOrOneToOneRelatedData(Result $parentEntityQuery, RelationMap $relation, array &$relatedData): Result
	{
		$table = $relation->getTableName();
		$query = $parentEntityQuery->$table()->via($relation->getRelationType()->getFieldName());
		foreach($query->fetchAll() as $value) {
			$relatedData[$relation->getEntityField()]['data'][$value->getOriginalId()] = $value->getData();
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
	private function getManyToManyRelatedData(Result $parentEntityQuery, RelationMap $relation, array &$relatedData): Result
	{
		$table = $relation->getRelationType()->getTable() . 'List';
		$relatedTableName = $relation->getTableName();

		//find relation table rows
		$relationsData = array_map(function($item) {
			return $item->getData();
		}, $parentEntityQuery->$table()->via($relation->getRelationType()->getColumnName())->fetchAll());

		$relatedTableData = [];
		$query = $parentEntityQuery
			->$table()
			->via($relation->getRelationType()->getColumnName())
			->$relatedTableName()
			->via($relation->getRelationType()->getReferencedColumnName());

		foreach($query->fetchAll() as $row) {
			$relatedTableData[$row->getOriginalId()] = $row->getData();
		}

		foreach($relationsData as $relationData) {
			$relatedData[$relation->getEntityField()]['data'][$relationData[$relation->getRelationType()->getColumnName()]][] =
				$relatedTableData[$relationData[$relation->getRelationType()->getReferencedColumnName()]];
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
	 * @param Result $query
	 * @param RelationMap $relation
	 * @param $relatedData
	 */
	private function getInnerData(array $innerWith = [], Result $query, RelationMap $relation, &$relatedData)
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
