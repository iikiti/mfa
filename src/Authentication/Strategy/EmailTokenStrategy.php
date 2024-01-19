<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

use iikiti\MfaBundle\Authentication\Interface\ChallengeInterface;

class EmailTokenStrategy extends AbstractTokenStrategy
{
	public function generateChallenge(
		#[\SensitiveParameter] string $secret = null
	): ChallengeInterface {
	}

	public function issueChallenge(
		ChallengeInterface $challenge = null
	): void {
	}

	public function validateChallenge(
		ChallengeInterface $challenge = null
	): bool {
	}

	public function generateSecret(): string
	{
	}
}
