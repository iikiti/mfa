<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

use iikiti\MfaBundle\Authentication\Interface\AuthenticationStrategy;

class EmailTokenStrategy implements AuthenticationStrategy
{
	public function generateChallenge(): void
	{
	}

	public function validateChallenge(): void
	{
	}
}
