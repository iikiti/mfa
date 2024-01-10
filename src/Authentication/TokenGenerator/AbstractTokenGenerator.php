<?php

namespace iikiti\MfaBundle\Authentication\TokenGenerator;

use iikiti\MfaBundle\Authentication\Interface\TokenGeneratorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractTokenGenerator implements TokenGeneratorInterface
{
	protected OptionsResolver $optionsResolver;

	public function __construct()
	{
		$this->optionsResolver = self::_generateOptionsResolver();
	}

	abstract protected static function _generateOptionsResolver(): OptionsResolver;
}
