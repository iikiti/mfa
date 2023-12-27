<?php

namespace iikiti\MfaBundle\Authentication\Event;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class GetUserMfaPreferencesEvent extends Event
{
	public const NAME = 'user.mfa.prefs';

	public function __construct(protected UserInterface $user)
	{
	}

	public function getUser(): UserInterface
	{
		return $this->user;
	}
}
