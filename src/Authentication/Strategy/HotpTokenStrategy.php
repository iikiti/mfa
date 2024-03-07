<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

use iikiti\MfaBundle\Authentication\Challenge;
use iikiti\MfaBundle\Authentication\Interface\ChallengeInterface;
use OTPHP\HOTP;
use OTPHP\HOTPInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * HOTP token strategy class generates and validates the necessary challenge.
 *
 * RFC 4226: HOTP: An HMAC-Based One-Time Password Algorithm:
 *     https://datatracker.ietf.org/doc/html/rfc4226
 */
class HotpTokenStrategy extends AbstractOtpTokenStrategy
{
	/**
	 * @return ChallengeInterface<HOTPInterface>
	 */
	public function generateChallenge(
		#[\SensitiveParameter] string $secret
	): ChallengeInterface {
		if (empty($secret)) {
			throw new AuthenticationException('Invalid secret.');
		}

		return new Challenge(HOTP::createFromSecret($secret));
	}

	/**
	 * @psalm-suppress MoreSpecificImplementedParamType
	 *
	 * @param ChallengeInterface<HOTPInterface> $challenge
	 */
	public function issueChallenge(
		ChallengeInterface $challenge
	): void {
	}

	/**
	 * @psalm-suppress MoreSpecificImplementedParamType
	 *
	 * @param ChallengeInterface<HOTPInterface> $challenge
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
		return HOTP::generate()->getSecret();
	}

	public function generateQrCode(): void
	{
	}
}
