<?php

namespace iikiti\MfaBundle\Authentication\Interface;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('iikiti_mfa.auth.strategy')]
/**
 * @template-covariant M of mixed
 */
interface AuthenticationStrategyInterface
{
	public function generateChallenge(
		#[\SensitiveParameter] string $secret = null
	): ChallengeInterface;

	public function issueChallenge(
		ChallengeInterface $challenge = null
	): void;

	public function validateChallenge(
		ChallengeInterface $challenge = null
	): bool;

	public function generateSecret(): null|string;
}
