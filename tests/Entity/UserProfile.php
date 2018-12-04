<?php

namespace Computools\CLightORM\Test\Entity;

use Computools\CLightORM\Entity\AbstractEntity;
use Computools\CLightORM\Mapper\Relations\OneToOne;
use Computools\CLightORM\Mapper\Types\IdType;
use Computools\CLightORM\Mapper\Types\StringType;

class UserProfile extends AbstractEntity
{
    public function getTable(): string
    {
        return 'user_profile';
    }

    public function getFields(): array
    {
        return [
            'id' => new IdType(),
            'user' => new OneToOne(new User(), 'user_id'),
            'first_name' => new StringType(),
            'last_name' => new StringType()
        ];
    }

	private $id;

	private $user;

	private $firstName;

	private $lastName;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user)
	{
		$this->user = $user;
	}

	/**
	 * @return mixed
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * @param mixed $firstName
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	/**
	 * @return mixed
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * @param mixed $lastName
	 */
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}
}