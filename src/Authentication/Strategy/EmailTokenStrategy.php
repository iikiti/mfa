<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

class EmailTokenStrategy extends AbstractTokenStrategy
{
	public function generateChallenge(): string
	{
	}

	public function issueChallenge(): void
	{
	}

	public function validateChallenge(): bool
	{
	}

	public function generateSecret(): null
	{
	}
}
