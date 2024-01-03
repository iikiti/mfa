<?php

namespace iikiti\MfaBundle\Authentication\Interface;

interface AuthenticationMethod
{
	public function generateChallenge(): void;

	public function validateChallenge(): void;
}
