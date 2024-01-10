<?php

namespace iikiti\MfaBundle\Authentication\Interface;

interface AuthenticationStrategy
{
	public function generateChallenge(): void;

	public function validateChallenge(): void;
}
