<?php

namespace iikiti\MfaBundle\Authentication;

use iikiti\MfaBundle\Authentication\Interface\ChallengeInterface;

/**
 * @template T of mixed
 *
 * @implements ChallengeInterface<T>
 */
final class Challenge implements ChallengeInterface
{
	/** @var T */
	private mixed $challenge;

	public function __construct(mixed $challenge)
	{
		$this->set($challenge);
	}

	/**
	 * @return T
	 */
	public function get(): mixed
	{
		return $this->challenge;
	}

	public function set(#[\SensitiveParameter] mixed $challenge): void
	{
		$this->challenge = $challenge;
	}
}
