<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

use iikiti\MfaBundle\Authentication\Interface\ChallengeInterface;
use iikiti\MfaBundle\Authentication\Interface\QrCodeInterface;
use OTPHP\OTPInterface;

/**
 * High level function that ensures OTP token strategies implement the necessary
 * challenge and validation methods.
 */
abstract class AbstractOtpTokenStrategy extends AbstractTokenStrategy implements QrCodeInterface
{
	/**
	 * @psalm-suppress MoreSpecificImplementedParamType
	 *
	 * @param ChallengeInterface<OTPInterface> $challenge
	 */
	abstract public function issueChallenge(
		ChallengeInterface $challenge
	): void;

	/**
	 * @psalm-suppress MoreSpecificImplementedParamType
	 *
	 * @param ChallengeInterface<OTPInterface> $challenge
	 *
	 * @return array<int,\Exception>
	 */
	abstract public function validateChallenge(
		ChallengeInterface $challenge,
		#[\SensitiveParameter] string $userInput
	): array;
}
