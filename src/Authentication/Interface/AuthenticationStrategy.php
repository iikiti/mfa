<?php

namespace iikiti\MfaBundle\Authentication\Interface;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('iikiti_mfa.auth.strategy')]
interface AuthenticationStrategy
{
	public function generateChallenge(): void;

	public function validateChallenge(): void;
}
