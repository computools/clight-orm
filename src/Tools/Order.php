<?php

namespace Computools\CLightORM\Tools;

use Computools\CLightORM\Exception\InvalidOrderDirectionException;

class Order
{
	public const SORT_ASC = 'ASC';
	public const SORT_DESC = 'DESC';
	public const SORT_DIRECTIONS_LIST = [
	    self::SORT_ASC,
        self::SORT_DESC
    ];

    public const DEFAULT_DIRECTION = self::SORT_ASC;

	private $direction;

	private $field;

	public function __construct(string $field, string $direction = self::DEFAULT_DIRECTION)
	{
	    if (!in_array($direction, self::SORT_DIRECTIONS_LIST)) {
	        throw new InvalidOrderDirectionException();
        }
		$this->field = $field;
		$this->direction = $direction;
	}

	public function getField(): string
	{
		return $this->field;
	}

	public function getDirection(): string
	{
		return $this->direction;
	}

	public function toArray(): array
	{
		return [
			'field' => $this->getField(),
			'direction' => $this->getDirection()
		];
	}
}