<?php

namespace iikiti\MfaBundle\Authentication\Event\Subscriber;

/*
 * TODO: This requires iikiti user object. Should alter to use base user
 * with ability to use a custom Closure.
 */

use iikiti\MfaBundle\Authentication\AuthenticationToken;
use iikiti\MfaBundle\Authentication\Event\GetUserMfaPreferencesEvent;
use iikiti\MfaBundle\Authentication\TokenInterface;
use iikiti\MfaBundle\Authentication\User\Property as MFProp;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\AuthenticationTokenCreatedEvent;

class AuthenticationTokenSubscriber implements EventSubscriberInterface
{
	public function __construct(private EventDispatcher $dispatcher)
	{
	}

	public static function getSubscribedEvents(): array
	{
		return [
			AuthenticationTokenCreatedEvent::class => 'onGeneralTokenCreated',
			GetUserMfaPreferencesEvent::class => 'getUserMfaPreferences',
		];
	}

	public function onGeneralTokenCreated(AuthenticationTokenCreatedEvent $event): void
	{
		$token = $event->getAuthenticatedToken();
		$user = $token->getUser();

		if (null === $user) {
			throw new AuthenticationException('User is invalid');
		}

		$prefs = $this->dispatcher->dispatch(new GetUserMfaPreferencesEvent($user));

		// TODO: Continue checking MFA properties and create MFA token
		if (!$mfaProperty->type) {
		}

		if (
			$token instanceof TokenInterface
		) {
			return;
		}

		$mfaToken = new AuthenticationToken();
		$mfaToken->setAssociatedToken($token);

		$event->setAuthenticatedToken($mfaToken);
	}

	protected static function __unserializeClassAlias(string $classname): void
	{
		// TODO: Make this more strict
		class_alias(MFProp::class, $classname);
	}

	// TODO: This is temporary until I can add the event listener to the main application.
	public function getUserMfaPreferences(GetUserMfaPreferencesEvent $event): void
	{
		$user = $event->getUser();

		// IF user is not an iikiti user, return null

		if (false == $user->getProperties()->containsKey(MFProp::KEY)) {
			return; // User does not have MFA preferences
		}

		spl_autoload_register(
			$unserializeLoadHandler = static function (string $classname): void {
				self::__unserializeClassAlias($classname);
			}
		);
		/** @var MFProp|\__PHP_Incomplete_Class|false $mfaProperty */
		$mfaProperty = unserialize($user->getProperties()->get(MFProp::KEY)->getValue());
		spl_autoload_unregister($unserializeLoadHandler);

		if (false === $mfaProperty || false == ($mfaProperty instanceof MFProp)) {
			throw new AuthenticationException('User has invalid or missing MFA preferences');
		}
	}
}
