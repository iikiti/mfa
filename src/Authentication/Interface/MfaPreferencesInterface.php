<?php

namespace iikiti\MfaBundle\Authentication\Interface;

interface MfaPreferencesInterface
{
	public const MFA_KEY = 'mfa';

	public function getMultifactorPreferences(): array|null;

	public function setMultifactorPreferences(array $preferences): void;
}
