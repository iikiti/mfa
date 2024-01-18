<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

use iikiti\MfaBundle\Authentication\Challenge;
use iikiti\MfaBundle\Authentication\Interface\ChallengeInterface;
use OTPHP\OTPInterface;
use OTPHP\TOTP;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * RFC 6238: Time-Based One-Time Password Algorithm (TOTP):
 *     https://datatracker.ietf.org/doc/html/rfc6238
 */
class TotpTokenStrategy extends HotpTokenStrategy
{
	/**
	 * @return ChallengeInterface<OTPInterface>
	 */
	public function generateChallenge(
		#[\SensitiveParameter] string $secret = null
	): ChallengeInterface {
		if (null === $secret || empty($secret)) {
			throw new AuthenticationException('Invalid secret.');
		}

		return new Challenge(TOTP::createFromSecret($secret));
	}

	/**
	 * @param ChallengeInterface<OTPInterface>|null $challenge
	 */
	public function issueChallenge(
		ChallengeInterface $challenge = null
	): void {
	}

	/**
	 * @param ChallengeInterface<OTPInterface>|null $challenge
	 */
	public function validateChallenge(
		ChallengeInterface $challenge = null
	): bool {
	}

	public function generateSecret(): string
	{
		return TOTP::generate()->getSecret();
	}

	public function generateQrCode(): void
	{
	}
}
