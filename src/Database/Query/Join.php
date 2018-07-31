<?php

namespace Computools\CLightORM\Database\Query;

class Join
{
	private $type;
	private $table;
	private $condition;
	private $alias;

	public function __construct(string $type, string $table, string $condition, string $alias = null)
	{
		$this->type = $type;
		$this->table = $table;
		$this->condition = $condition;
		$this->alias = $alias;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function getTable(): string
	{
		return $this->table;
	}

	public function getCondition(): string
	{
		return $this->condition;
	}

	public function getAlias(): ?string
	{
		return $this->alias ? $this->alias : $this->table;
	}
}