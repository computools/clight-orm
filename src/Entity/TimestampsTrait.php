<?php

namespace Computools\CLightORM\Entity;

trait TimestampsTrait
{
	private $createdAt;

	private $updatedAt;

	public function getCreatedAt(): ?\DateTime
	{
		return $this->createdAt;
	}

	public function setCreatedAt(?\DateTime $createdAt = null): void
	{
		$this->createdAt = $createdAt;
	}

	public function getUpdatedAt(): ?\DateTime
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(?\DateTime $updatedAt = null): void
	{
		$this->updatedAt = $updatedAt;
	}
}