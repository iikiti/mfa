<?php

namespace iikiti\MfaBundle\Authentication\TokenGenerator;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validation;

class StringTokenGenerator extends AbstractSimpleTokenGenerator
{
	protected OptionsResolver $optionsResolver;

	public function __construct()
	{
		$this->optionsResolver = self::_generateOptionsResolver();
	}

	public function generate(array $options = []): string
	{
		$options = $this->optionsResolver->resolve($options);

		return (string) random_int($options['min'], $options['max']);
	}

	protected static function _generateOptionsResolver(): OptionsResolver
	{
		$resolver = new OptionsResolver();
		$resolver->setDefaults(
			[
				'min' => 5,
				'max' => 5,
			]
		);
		$resolver->setAllowedTypes('min', 'int');
		$resolver->setAllowedTypes('max', 'int');
		$resolver->setAllowedValues(
			'min',
			Validation::createIsValidCallable(
				new Length(['min' => 4, 'max' => PHP_INT_MAX])
			)
		);
		$resolver->setAllowedValues(
			'max',
			function (Options $options): bool {
				return Validation::createIsValidCallable(
					new Length(['min' => 4, 'max' => $options['min']])
				)($options['max']);
			}
		);

		return $resolver;
	}
}
