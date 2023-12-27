<?php

namespace iikiti\mfa\Authentication\Event\Subscriber;

use iikiti\CMS\Entity\Object\User;
use iikiti\mfa\Authentication\AuthenticationToken;
use iikiti\mfa\Authentication\TokenInterface;
use iikiti\mfa\Authentication\User\Property as MFProp;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\AuthenticationTokenCreatedEvent;

class AuthenticationTokenSubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents(): array
	{
		return [
			AuthenticationTokenCreatedEvent::class => 'onGeneralTokenCreated',
		];
	}

	public static function onGeneralTokenCreated(AuthenticationTokenCreatedEvent $event): void
	{
		$token = $event->getAuthenticatedToken();

		// TODO: Check user for multi-factor login requirement.
		/** @var User|null $user */
		$user = $token->getUser();

		if (null === $user) {
			throw new AuthenticationException('User is invalid');
		} elseif (false == $user->getProperties()->containsKey(MFProp::KEY)) {
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
}
