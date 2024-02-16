<?php

namespace iikiti\MfaBundle\Authentication\Event\Subscriber;

use iikiti\MfaBundle\Authentication\AuthenticationToken;
use iikiti\MfaBundle\Authentication\Enum\ConfigurationTypeEnum;
use iikiti\MfaBundle\Authentication\Interface\MfaConfigurationServiceInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Event\AuthenticationTokenCreatedEvent;

class AuthenticationTokenSubscriber implements EventSubscriberInterface
{
	public function __construct(
		private EventDispatcherInterface $dispatcher,
		private ContainerBagInterface $params,
		#[TaggedIterator('mfa.config')]
		private iterable $mfaConfigIterator,
		private KernelInterface $kernel
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

		if (null === $user) {
			throw new AuthenticationException('User is invalid');
		}

		$configService = $this->__getApplicationConfiguration(
			$this->mfaConfigIterator,
			(new \ReflectionClass($this->kernel))->getNamespaceName()
		);

		$appPrefs = $configService->getMultifactorPreferences(
			ConfigurationTypeEnum::APPLICATION,
			$user
		);
		$sitePrefs = $configService->getMultifactorPreferences(
			ConfigurationTypeEnum::SITE,
			$user
		);
		$userPrefs = $configService->getMultifactorPreferences(
			ConfigurationTypeEnum::USER,
			$user
		);

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

	private function __getApplicationConfiguration(
		iterable $configIterable,
		string $appNamespace
	): MfaConfigurationServiceInterface {
		$total = 0;
		foreach ($configIterable as $config) {
			++$total;
			if (str_starts_with($config::class, $appNamespace)) {
				return $config;
			}
		}

		if ($total > 0) {
			$message = 'No valid configuration service found. '.
				'The service must be part of the application, not a bundle.';
			throw new AuthenticationException($message);
		} else {
			throw new AuthenticationException('No configuration service found.');
		}
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
