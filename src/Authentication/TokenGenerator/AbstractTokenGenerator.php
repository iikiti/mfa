<?php

namespace iikiti\MfaBundle\Authentication\TokenGenerator;

use iikiti\MfaBundle\Authentication\Interface\TokenGeneratorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * High-level abstract token generator.
 * Creates options resolver and ensures toke generators implement a way to
 * generate the resolver.
 */
abstract class AbstractTokenGenerator implements TokenGeneratorInterface
{
	protected OptionsResolver $optionsResolver;

	public function __construct()
	{
		$this->optionsResolver = static::_generateOptionsResolver();
	}

	abstract protected static function _generateOptionsResolver(): OptionsResolver;
}
