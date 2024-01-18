<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

use iikiti\MfaBundle\Authentication\Challenge;
use iikiti\MfaBundle\Authentication\Interface\ChallengeInterface;
use iikiti\MfaBundle\Authentication\Interface\QrCodeInterface;
use OTPHP\HOTP;
use OTPHP\OTPInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * RFC 4226: HOTP: An HMAC-Based One-Time Password Algorithm:
 *     https://datatracker.ietf.org/doc/html/rfc4226
 */
class HotpTokenStrategy extends AbstractTokenStrategy implements QrCodeInterface
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

		return new Challenge(HOTP::createFromSecret($secret));
	}

	/**
	 * @psalm-suppress MoreSpecificImplementedParamType
	 *
	 * @param ChallengeInterface<OTPInterface>|null $challenge
	 */
	public function issueChallenge(
		ChallengeInterface $challenge = null
	): void {
	}

	/**
	 * @psalm-suppress MoreSpecificImplementedParamType
	 *
	 * @param ChallengeInterface<OTPInterface>|null $challenge
	 */
	public function validateChallenge(
		ChallengeInterface $challenge = null
	): bool {
	}

	public function generateSecret(): string
	{
		return HOTP::generate()->getSecret();
	}

	public function generateQrCode(): void
	{
	}
}
