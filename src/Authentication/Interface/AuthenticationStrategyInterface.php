<?php

namespace iikiti\MfaBundle\Authentication\Interface;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

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

	public function generateSecret(): null|string;
}
