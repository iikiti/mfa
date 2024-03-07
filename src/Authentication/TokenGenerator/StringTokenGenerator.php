<?php

namespace iikiti\MfaBundle\Authentication\TokenGenerator;

use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

/**
 * String token generator. Generates and validates random integer tokens and
 * returns them as strings.
 */
class StringTokenGenerator extends IntegerTokenGenerator
{
	public function __construct()
	{
		parent::__construct();
	}

	public function validate(string|int $requestToken, string|int $storedToken): ConstraintViolationListInterface
	{
		$validator = Validation::createValidator();
		$tokenValidConstraint = new IsTrue(null, 'The token is not valid.');

		return $validator->validate(
			password_verify((string) $storedToken, (string) $requestToken),
			$tokenValidConstraint
		);
	}
}
