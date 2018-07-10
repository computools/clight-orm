<?php

namespace Computools\CLightORM\Tools;

class Pagination
{
	const DEFAULT_LIMIT = 20;
	const DEFAULT_OFFSET = 0;

	private $page;

	private $perPage = self::DEFAULT_LIMIT;

	private $limit = self::DEFAULT_LIMIT;

	private $offset = self::DEFAULT_OFFSET;

	private $isPagination = true;

	public function setPagination(int $page = 1, int $perPage = self::DEFAULT_LIMIT): self
	{
		$this->page = $page;
		$this->perPage = $perPage;
		return $this;
	}

	public function setLimitOffset(int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): self
	{
		$this->limit = $limit;
		$this->offset = $offset;
		$this->isPagination = false;
		return $this;
	}

	public function getPage(): int
	{
		return $this->page;
	}

	public function getPerPage(): int
	{
		return $this->perPage;
	}

	public function getLimit(): int
	{
		return $this->limit;
	}

	public function getOffset(): int
	{
		return $this->offset;
	}

	public function isPagination(): bool
	{
		return $this->isPagination;
	}
}