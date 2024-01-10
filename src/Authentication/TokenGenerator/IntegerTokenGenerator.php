<?php

namespace iikiti\MfaBundle\Authentication\TokenGenerator;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class IntegerTokenGenerator extends AbstractSimpleTokenGenerator
{
	public function __construct()
	{
		parent::__construct();
	}

	public function generate(array $options = []): int
	{
		$options = $this->optionsResolver->resolve($options);

		return random_int($options['min'], $options['max']);
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

	public function validate(int|string $requestToken, int|string $storedToken): ConstraintViolationListInterface
	{
		$validator = Validation::createValidator();
		$intConstraint = new Type('int', 'The token must be an integer.');
		$tokenValidConstraint = new IsTrue(null, 'The token is not valid.');

		$constraints = new Collection(
			[
				'requestToken' => $intConstraint,
				'storedToken' => $intConstraint,
				'tokenValid' => $tokenValidConstraint,
			]
		);

		return $validator->validate(
			[
				'requestToken' => $requestToken,
				'storedToken' => $storedToken,
				'tokenValid' => password_verify((string) $storedToken, (string) $requestToken),
			],
			$constraints
		);
	}
}
