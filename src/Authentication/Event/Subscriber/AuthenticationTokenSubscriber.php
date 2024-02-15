<?php

namespace iikiti\MfaBundle\Authentication\Event\Subscriber;

use Doctrine\ORM\EntityManagerInterface;
use iikiti\MfaBundle\Authentication\AuthenticationToken;
use iikiti\MfaBundle\Authentication\Interface\ApplicationSubordinateInterface;
use iikiti\MfaBundle\Authentication\Interface\MfaPreferencesInterface;
use iikiti\MfaBundle\iikitiMultifactorAuthenticationBundle as Bundle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\AuthenticationTokenCreatedEvent;

class AuthenticationTokenSubscriber implements EventSubscriberInterface
{
	public function __construct(
		private EventDispatcherInterface $dispatcher,
		private ContainerBagInterface $params,
		private EntityManagerInterface $entityManager
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

		/** @var class-string $repositoryClass */
		$repositoryClass = $this->params->get(Bundle::SITE_ENTITY_KEY);
		$siteRepository = $this->entityManager->getRepository($repositoryClass);
		if (
			!(
				$siteRepository instanceof MfaPreferencesInterface ||
				$siteRepository instanceof ApplicationSubordinateInterface
			)
		) {
			throw new \Exception('Repository class must implement '.MfaPreferencesInterface::class.' and '.ApplicationSubordinateInterface::class);
		}
		if (null === $user) {
			throw new AuthenticationException('User is invalid');
		} elseif (!($user instanceof MfaPreferencesInterface)) {
			throw new \Exception('User class must implement '.MfaPreferencesInterface::class);
		}

		$appPrefs = $siteRepository->getApplicationRepository()->getMultifactorPreferences() ?? [];
		$sitePrefs = $siteRepository->getMultifactorPreferences() ?? [];
		$userPrefs = $user->getMultifactorPreferences() ?? [];

		if (false == $this->__checkPreferences($appPrefs, $sitePrefs, $userPrefs)) {
			return; // Site or user not configured for MFA
		}
		// $this->__checkAuthData($authData);

		if (
			$token instanceof TokenInterface
		) {
			return;
		}

		$mfaToken = new AuthenticationToken();
		$mfaToken->setAssociatedToken($token);

		$event->setAuthenticatedToken($mfaToken);
	}

	private function __checkPreferences(array $application, array $site, array $user): bool
	{
		$accessor = PropertyAccess::createPropertyAccessor();

		return false;
	}

	private function __checkAuthData(array $authData): void
	{
	}
}
