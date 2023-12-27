<?php

namespace iikiti\MfaBundle\Entity;

interface UserInterface
{
	public function getMultifactorPreferences(): array;
}
