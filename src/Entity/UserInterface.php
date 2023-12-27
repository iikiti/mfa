<?php

namespace iikiti\MfaBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

interface UserInterface extends SecurityUserInterface
{
	public function getMultifactorPreferences(): array;
}
