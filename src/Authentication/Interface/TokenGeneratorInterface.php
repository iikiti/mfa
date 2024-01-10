<?php

namespace iikiti\MfaBundle\Authentication\Interface;

interface TokenGeneratorInterface
{
	/**
	 * @param array<string,mixed> $options
	 */
	public function generate(array $options = []): string|int;
}
