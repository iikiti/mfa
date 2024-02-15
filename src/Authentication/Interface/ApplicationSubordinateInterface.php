<?php

namespace iikiti\MfaBundle\Authentication\Interface;

interface ApplicationSubordinateInterface
{
	public function getApplicationRepository(): object;
}
