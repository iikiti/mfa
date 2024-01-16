<?php

namespace iikiti\MfaBundle\Authentication\Event\Subscriber;

/*
 * TODO: This requires iikiti user object. Should alter to use base user
 * with ability to use a custom Closure.
 */

use iikiti\MfaBundle\Authentication\AuthenticationToken;
use iikiti\MfaBundle\Entity\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\AuthenticationTokenCreatedEvent;

class AuthenticationTokenSubscriber implements EventSubscriberInterface
{
	public function __construct(private EventDispatcherInterface $dispatcher)
	{
	}

	public static function getSubscribedEvents(): array
	{
		return [
			AuthenticationTokenCreatedEvent::class => 'onGeneralTokenCreated',
		];
	}

	public function onGeneralTokenCreated(AuthenticationTokenCreatedEvent $event): void
	{
		$token = $event->getAuthenticatedToken();
		$user = $token->getUser();

		if (null === $user || false == ($user instanceof UserInterface)) {
			throw new AuthenticationException('User is invalid');
		}

		/** @var UserInterface $user */
		$prefs = $user->getMultifactorPreferences();

		if (null === $prefs || [] === $prefs) {
			throw new AuthenticationException('User has invalid or missing MFA preferences');
		}

		$authData = $prefs['~'.($prefs['type'] ?? '')] ?? false;
		if ($prefs['type'] ?? '' == '' || false == is_array($authData) || empty($authData)) {
			throw new AuthenticationException('Invalid authentication data.');
		}

		$this->_checkAuthData($authData);

		if (
			$token instanceof TokenInterface
		) {
			return;
		}

		$mfaToken = new AuthenticationToken();
		$mfaToken->setAssociatedToken($token);

		$event->setAuthenticatedToken($mfaToken);
	}

	private function _checkAuthData(array $authData): void
	{
	}
}
