<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

use iikiti\MfaBundle\Authentication\Interface\ChallengeInterface;

class EmailTokenStrategy extends AbstractTokenStrategy
{
	public function generateChallenge(
		#[\SensitiveParameter] string $secret
	): ChallengeInterface {
	}

	public function issueChallenge(
		ChallengeInterface $challenge
	): void {
	}

	public function validateChallenge(
		ChallengeInterface $challenge,
		#[\SensitiveParameter] string $secret
	): array {
	}

	public function generateSecret(): string
	{
	}
}
