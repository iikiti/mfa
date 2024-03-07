<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

use iikiti\MfaBundle\Authentication\Challenge;
use iikiti\MfaBundle\Authentication\Exception\AccessDeniedException;
use iikiti\MfaBundle\Authentication\Interface\ChallengeInterface;
use iikiti\MfaBundle\Authentication\TokenGenerator\StringTokenGenerator;

/**
 * Email token strategy that handles the challenge via an email
 * containing the code the user is required to enter.
 */
class EmailTokenStrategy extends AbstractTokenStrategy
{
	protected StringTokenGenerator $stringGenerator;

	public function __construct()
	{
		$this->stringGenerator = new StringTokenGenerator();
	}

	/**
	 * Generate challenge (currently simple randomly generated 6-digit code).
	 */
	public function generateChallenge(
		#[\SensitiveParameter] string $secret
	): ChallengeInterface {
		return new Challenge($this->generateSecret());
	}

	/**
	 * Send email.
	 */
	public function issueChallenge(
		ChallengeInterface $challenge
	): void {
	}

	public function validateChallenge(
		ChallengeInterface $challenge,
		#[\SensitiveParameter] string $userInput
	): array {
		$errors = [];
		if ($this->stringGenerator->validate($challenge->get(), $userInput)->count() > 0) {
			$errors[] = new AccessDeniedException('Challenge validation failed.');
		}

		return $errors;
	}

	public function generateSecret(int $length = 6): string
	{
		return $this->stringGenerator->generate(['min' => 0, 'max' => 9, 'length' => $length]);
	}
}
