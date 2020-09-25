<?php

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistsValueInEntityValidator extends ConstraintValidator
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function validate($value, Constraint $constraint)
	{
		$entityRepository = $this->entityManager->getRepository($constraint->entityClass);

		if (!is_scalar($constraint->field)) {
			throw new InvalidArgumentException('"field" parameter should be any scalar type');
		}

		$searchResults = $entityRepository
			->findBy([
				$constraint->field => $value,
			]);

		if (count($searchResults) === 0) {
			$this->context
				->buildViolation($constraint->message)
				->addViolation();
		}
	}
}
