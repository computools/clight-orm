<?php

namespace Computools\CLightORM\Entity;

trait TimestampsTrait
{
	private $createdAt;

	private $updatedAt;

	public function getCreatedAt(): \DateTime
	{
		return $this->createdAt;
	}

	public function setCreatedAt(\DateTime $createdAt): void
	{
		$this->createdAt = $createdAt;
	}

	public function getUpdatedAt(): ?\DateTime
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(?\DateTime $updatedAt): void
	{
		$this->updatedAt = $updatedAt;
	}
}