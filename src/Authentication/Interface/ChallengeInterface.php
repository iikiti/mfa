<?php

namespace iikiti\MfaBundle\Authentication\Interface;

/**
 * @template-covariant T of mixed
 */
interface ChallengeInterface
{
	/**
	 * @return T
	 */
	public function get(): mixed;

	public function set(#[\SensitiveParameter] mixed $challenge): void;
}
