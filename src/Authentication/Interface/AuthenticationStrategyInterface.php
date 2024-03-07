<?php

namespace iikiti\MfaBundle\Authentication\Interface;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Identifies and tags classes that can provide a multi-factor authentication method.
 * Ensures the required challenge and validation methods are implemented.
 */
#[AutoconfigureTag('iikiti_mfa.auth.strategy')]
interface AuthenticationStrategyInterface
{
	public function generateChallenge(
		#[\SensitiveParameter] string $secret
	): ChallengeInterface;

	public function issueChallenge(
		ChallengeInterface $challenge
	): void;

	/**
	 * @return array<int,\Exception>
	 */
	public function validateChallenge(
		ChallengeInterface $challenge,
		#[\SensitiveParameter] string $userInput
	): array;

	public function generateSecret(): ?string;
}
