<?php

namespace iikiti\MfaBundle\Authentication\Event\Subscriber;

use iikiti\MfaBundle\Authentication\AuthenticationToken;
use iikiti\MfaBundle\Authentication\Enum\ConfigurationTypeEnum;
use iikiti\MfaBundle\Authentication\Interface\MfaConfigurationServiceInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\AuthenticationTokenCreatedEvent;

/**
 * Intercepts the token created when the user logs in.
 */
class AuthenticationTokenSubscriber implements EventSubscriberInterface
{
	/**
	 * Injects necessary services.
	 */
	public function __construct(
		private EventDispatcherInterface $dispatcher,
		private ContainerBagInterface $params,
		#[AutowireIterator('mfa.config')]
		private iterable $mfaConfigIterator,
		private KernelInterface $kernel,
	) {
	}

	/**
	 * Identifies events that are subscribed to.
	 *
	 * Currently: AuthenticationTokenCreatedEvent - This is fired after the user
	 * logs in and an authentication token is generated.
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			AuthenticationTokenCreatedEvent::class => 'onGeneralTokenCreated',
		];
	}

	/**
	 * When a token is created, this method acquires the preferences for the
	 * application, site, and user. They are filtered to get the final set of
	 * preferences and verifies them against the current request. If a
	 * multi-factor authentication method is required, a new token is created
	 * as a proxy and placed in front of the existing one.
	 */
	public function onGeneralTokenCreated(
		AuthenticationTokenCreatedEvent $event,
	): void {
		$token = $event->getAuthenticatedToken();
		$user = $token->getUser();

		if (null === $user) {
			throw new AuthenticationException('User is invalid');
		}

		list($appPrefs, $sitePrefs, $userPrefs) = self::__getConfigurations(
			$this->mfaConfigIterator,
			$user,
			(new \ReflectionClass($this->kernel))->getNamespaceName()
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

	/**
	 * Acquires the application, site, and user preferences via a checked
	 * configuration service.
	 */
	private static function __getConfigurations(
		iterable $mfaConfigIterator,
		UserInterface $user,
		string $appNamespace,
	) {
		$configService = self::__getApplicationConfiguration(
			$mfaConfigIterator,
			$appNamespace
		);

		return [
			$configService->getMultifactorPreferences(
				ConfigurationTypeEnum::APPLICATION,
				$user
			),
			$configService->getMultifactorPreferences(
				ConfigurationTypeEnum::SITE,
				$user
			),
			$configService->getMultifactorPreferences(
				ConfigurationTypeEnum::USER,
				$user
			),
		];
	}

	/**
	 * Acquires the configuration service and checks to ensure it came from the
	 * primary application and not a bundle or extension. This is to prevent
	 * hijacking of the service. If you want to allow a third-party
	 * to provide the configuration, use the application configuration service
	 * as a proxy but be aware that it is a greater security risk.
	 */
	private static function __getApplicationConfiguration(
		iterable $configIterable,
		string $appNamespace,
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

	/**
	 * Checks that the current user can use a multi-factor authentication
	 * service and which one to use.
	 */
	private function __checkPreferences(array $application, array $site, array $user): bool
	{
		$accessor = PropertyAccess::createPropertyAccessor();

		return false;
	}

	/**
	 * Checks the authentication data against the current request.
	 */
	private function __checkAuthData(array $authData): void
	{
	}
}
