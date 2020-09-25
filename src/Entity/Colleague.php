<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="colleagues")
 * @ORM\Entity(repositoryClass="App\Repository\ColleagueRepository")
 *
 * @Vich\Uploadable
 */
class Colleague
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
	 * @var string|null $picture
	 *
	 * @ORM\Column(type="string", length=199, nullable=true)
	 */
	private ?string $picture = null;

	/**
	 * NOTE: This is not a mapped field of entity metadata, just a simple property.
	 *
	 * @Vich\UploadableField(mapping="colleague_picture", fileNameProperty="picture", size="pictureSize")
	 *
	 * @var File|null
	 */
	private ?File $pictureFile = null;

	/**
	 * @var int|null
	 */
	private ?int $pictureSize;

	/**
	 * @var string $name
	 *
	 * @ORM\Column(type="string", length=199)
	 *
	 * @Assert\NotBlank
	 */
	private string $name;

	/**
	 * @var string|null $role
	 *
	 * @ORM\Column(type="string", length=199, nullable=true)
	 */
	private ?string $role;

	/**
	 * @var string|null $notes
	 *
	 * @ORM\Column(type="text", nullable=true)
	 */
	private ?string $notes;

	/**
	 * @var null|UserInterface $user
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="colleagues")
	 */
	private ?UserInterface $user;

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
	public function getPicture(): ?string
	{
		return $this->picture;
	}

	/**
	 * @param string|null $picture
	 */
	public function setPicture(?string $picture): void
	{
		$this->picture = $picture;
	}

	/**
	 * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
	 * of 'UploadedFile' is injected into this setter to trigger the update. If this
	 * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
	 * must be able to accept an instance of 'File' as the bundle will inject one here
	 * during Doctrine hydration.
	 *
	 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $pictureFile
	 */
	public function setPictureFile(?File $pictureFile): void
	{
		$this->pictureFile = $pictureFile;
	}

	/**
	 * @return File|null
	 */
	public function getPictureFile(): ?File
	{
		return $this->pictureFile;
	}

	/**
	 * @param int|null $pictureSize
	 */
	public function setPictureSize(?int $pictureSize): void
	{
		$this->pictureSize = $pictureSize;
	}

	/**
	 * @return int|null
	 */
	public function getPictureSize(): ?int
	{
		return $this->pictureSize;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getRole(): ?string
	{
		return $this->role;
	}

	/**
	 * @param string|null $role
	 */
	public function setRole(?string $role): void
	{
		$this->role = $role;
	}

	/**
	 * @return string
	 */
	public function getNotes(): ?string
	{
		return $this->notes;
	}

	/**
	 * @param string|null $notes
	 */
	public function setNotes(?string $notes): void
	{
		$this->notes = $notes;
	}

	/**
	 * @return null|UserInterface
	 */
	public function getUser(): ?UserInterface
	{
		return $this->user;
	}

	/**
	 * @param UserInterface|null $user
	 */
	public function setUser(?UserInterface $user): void
	{
		$this->user = $user;
	}
}