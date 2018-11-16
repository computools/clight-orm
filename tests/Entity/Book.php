<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\MapperInterface;
use Computools\CLightORM\Test\Mapper\BookMapper;

class Book extends AbstractEntity
{
	public function getMapper(): MapperInterface
	{
		return new BookMapper();
	}

	protected $allowedFields = [
	    'name',
        'price'
    ];

	public $id;

	public $name;

	public $authors;

	public $themes;

	public $price;

	public $data;

	public $dataBinary;
}