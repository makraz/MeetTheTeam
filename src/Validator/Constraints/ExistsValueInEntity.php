<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ExistsValueInEntity extends Constraint
{
	public $message = 'This value is not exist.';
	public $entityClass;
	public $field;

	public function getRequiredOptions()
	{
		return ['entityClass', 'field'];
	}

	public function getTargets()
	{
		return self::PROPERTY_CONSTRAINT;
	}

	public function validatedBy()
	{
		return get_class($this) . 'Validator';
	}
}
