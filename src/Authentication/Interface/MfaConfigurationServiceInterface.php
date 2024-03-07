<?php

namespace iikiti\MfaBundle\Authentication\Interface;

use iikiti\MfaBundle\Authentication\Enum\ConfigurationTypeEnum;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Ensures multi-factor configuration services implement the required
 * methods and are tagged.
 */
#[AutoconfigureTag('mfa.config')]
interface MfaConfigurationServiceInterface
{
	public function getMultifactorPreferences(ConfigurationTypeEnum $type, UserInterface $user): array;

	public function setMultifactorPreferences(
		ConfigurationTypeEnum $type,
		array $preferences
	): void;
}
