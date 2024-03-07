<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

use iikiti\MfaBundle\Authentication\Challenge;
use iikiti\MfaBundle\Authentication\Interface\ChallengeInterface;
use OTPHP\TOTP;
use OTPHP\TOTPInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Totp token strategy that generates, issues and validates the necessary
 * challenge.
 *
 * RFC 6238: Time-Based One-Time Password Algorithm (TOTP):
 *     https://datatracker.ietf.org/doc/html/rfc6238
 */
class TotpTokenStrategy extends AbstractOtpTokenStrategy
{
	/**
	 * @return ChallengeInterface<TOTPInterface>
	 */
	public function generateChallenge(
		#[\SensitiveParameter] string $secret
	): ChallengeInterface {
		if (empty($secret)) {
			throw new AuthenticationException('Invalid secret.');
		}

		return new Challenge(TOTP::createFromSecret($secret));
	}

	/**
	 * @psalm-suppress MoreSpecificImplementedParamType
	 *
	 * @param ChallengeInterface<TOTPInterface> $challenge
	 */
	public function issueChallenge(
		ChallengeInterface $challenge
	): void {
	}

	/**
	 * @psalm-suppress MoreSpecificImplementedParamType
	 *
	 * @param ChallengeInterface<TOTPInterface> $challenge
	 *
	 * @return array<int,\Exception>
	 */
	public function validateChallenge(
		ChallengeInterface $challenge,
		#[\SensitiveParameter] string $userInput
	): array {
		$errors = [];

		if (empty($userInput)) {
			$errors[] = new AuthenticationException('Invalid challenge or secret.');

			return $errors;
		}

		if (false == $challenge->get()->verify($userInput)) {
			$errors[] = new AuthenticationException('Challenge is incorrect.');
		}

		return $errors;
	}

	public function generateSecret(): string
	{
		return TOTP::generate()->getSecret();
	}

	public function generateQrCode(): void
	{
	}
}
