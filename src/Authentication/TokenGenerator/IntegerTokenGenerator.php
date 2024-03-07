<?php

namespace iikiti\MfaBundle\Authentication\TokenGenerator;

/**
 * Integer token generator. Generates and validates random integer tokens.
 */
class IntegerTokenGenerator extends NumericTokenGenerator
{
	public function __construct()
	{
		parent::__construct();
	}

	public function generate(array $options = []): int
	{
		$options = $this->optionsResolver->resolve($options);

		return $this->_generate($options);
	}
}
