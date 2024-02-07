<?php

namespace iikiti\MfaBundle\Authentication\Event\Subscriber;

/*
 * TODO: This requires iikiti user object. Should alter to use base user
 * with ability to use a custom Closure.
 */

use Doctrine\ORM\EntityManager;
use iikiti\MfaBundle\Authentication\AuthenticationToken;
use iikiti\MfaBundle\Authentication\Interface\MfaPreferencesInterface;
use iikiti\MfaBundle\iikitiMultifactorAuthenticationBundle as Bundle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\AuthenticationTokenCreatedEvent;

class AuthenticationTokenSubscriber implements EventSubscriberInterface
{
	public function __construct(
		private EventDispatcherInterface $dispatcher,
		private ContainerBagInterface $params,
	) {
	}

	public static function getSubscribedEvents(): array
	{
		return [
			AuthenticationTokenCreatedEvent::class => 'onGeneralTokenCreated',
		];
	}

	public function onGeneralTokenCreated(
		AuthenticationTokenCreatedEvent $event,
	): void {
		$token = $event->getAuthenticatedToken();
		$user = $token->getUser();

		// dump($this->em->getRepository());
		// dump($this->container->get($this->params->get(Bundle::SITE_REPOSITORY_KEY)));
		// dump($this->entityManager->getRepository($this->params->get(Bundle::SITE_REPOSITORY_KEY)));
		// if (null === $this->siteRepository) {
		//	throw new AuthenticationException('Site repository is invalid');
		// }

		if (null === $user || false == ($user instanceof MfaPreferencesInterface)) {
			throw new AuthenticationException('User is invalid');
		}

		/** @var MfaPreferencesInterface $user */
		$prefs = $user->getMultifactorPreferences();

		if (null === $prefs || [] === $prefs) {
			throw new AuthenticationException('User has invalid or missing MFA preferences');
		}

		$authData = $prefs['~'.($prefs['type'] ?? '')] ?? false;
		if (($prefs['type'] ?? '') == '' || [] === $authData) {
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
