<?php

namespace iikiti\MfaBundle\Entity;

interface UserInterface
{
	public const MFA_KEY = 'mfa';

	public function getMultifactorPreferences(): array|null;
}
