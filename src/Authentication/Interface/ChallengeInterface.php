<?php

namespace iikiti\MfaBundle\Authentication\Interface;

/**
 * Ensures challenges have the required methods.
 *
 * @template T
 */
interface ChallengeInterface
{
	/**
	 * @template-covariant T of mixed
	 *
	 * @return T
	 */
	public function get(): mixed;

	/**
	 * @param T $challenge
	 */
	public function set(#[\SensitiveParameter] mixed $challenge): void;
}
