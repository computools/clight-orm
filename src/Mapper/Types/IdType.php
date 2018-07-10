<?php

namespace Computools\CLightORM\Mapper\Types;

class IdType extends IntegerType
{
	private $identifierField;

	public function __construct(?string $identifierField = null)
	{
		parent::__construct($identifierField);
		$this->identifierField = $identifierField;
	}

	public function getIdentifierField():? string
	{
		return $this->identifierField;
	}
}