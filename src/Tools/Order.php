<?php

namespace Computools\CLightORM\Tools;

class Order
{
	public const SORT_ASC = 'ASC';
	public const SORT_DESC = 'DESC';

    public const DEFAULT_DIRECTION = self::SORT_ASC;

	private $direction;

	private $field;

	public function __construct(string $field, string $direction = self::DEFAULT_DIRECTION)
	{
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