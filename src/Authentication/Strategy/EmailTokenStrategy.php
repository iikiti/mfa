<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

class EmailTokenStrategy extends AbstractTokenStrategy
{
	public function generateChallenge(): void
	{
	}

	public function validateChallenge(): void
	{
	}
}
