<?php

namespace Computools\CLightORM\Tools;

class RelatedDataSet
{
    /**
     * @var RelatedData[]
     */
    private $relatedData = [];

    public function __construct(array $relations = [])
    {
        if (!empty($relations)) {
            $this->parseRelationArray($relations);
        }
    }

    private function parseRelationArray(array $relations)
    {
        foreach ($relations as $key => $relation) {
            if (is_array($relation)) {
                $this->addRelatedData(
                    (new RelatedData($key))->setRelatedDataSet(
                        (new RelatedDataSet($relation))
                    )
                );
            } else {
                $this->addRelatedData(new RelatedData($relation));
            }
        }
    }

    public function addRelatedData(RelatedData $relatedData): RelatedDataSet
    {
        $this->relatedData[] = $relatedData;
        return $this;
    }

    /**
     * @return RelatedData[]
     */
    public function getRelatedData(): array
    {
        return $this->relatedData;
    }
}
