<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User
{
	/**
	 * @var int $id
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string $firstName
	 *
	 * @ORM\Column(type="string", length=199)
	 *
	 * @Assert\NotBlank
	 */
	private $fullName;

	/**
	 * @var string $email
	 *
	 * @ORM\Column(type="string", length=199, unique=true)
	 *
	 * @Assert\NotBlank
	 * @Assert\Email
	 */
	private $email;

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getFullName(): string
	{
		return $this->fullName;
	}

	/**
	 * @param string $fullName
	 */
	public function setFullName(string $fullName): void
	{
		$this->fullName = $fullName;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail(string $email): void
	{
		$this->email = $email;
	}
}