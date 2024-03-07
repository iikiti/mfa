<?php

namespace iikiti\MfaBundle\Authentication\Interface;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Ensures token generators implement the required interfaces.
 */
interface TokenGeneratorInterface
{
	/**
	 * @param array<string,mixed> $options
	 */
	public function generate(array $options = []): string|int;

	public function validate(string|int $requestToken, string|int $storedToken): ConstraintViolationListInterface;
}
