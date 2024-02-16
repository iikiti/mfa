<?php

namespace iikiti\MfaBundle\Authentication\Interface;

use iikiti\MfaBundle\Authentication\Enum\ConfigurationTypeEnum;

interface MfaConfigurationServiceInterface
{
	public function getMultifactorPreferences(ConfigurationTypeEnum $type): array;

	public function setMultifactorPreferences(
		ConfigurationTypeEnum $type,
		array $preferences
	): void;
}
