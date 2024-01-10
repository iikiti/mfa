<?php

namespace iikiti\MfaBundle\Authentication\TokenGenerator;

abstract class AbstractSimpleTokenGenerator extends AbstractTokenGenerator
{
	abstract public function generate(array $options = []): string|int;
}
