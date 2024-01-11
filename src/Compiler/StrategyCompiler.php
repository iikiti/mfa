<?php

namespace iikiti\MfaBundle\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StrategyCompiler implements CompilerPassInterface
{
	public function process(ContainerBuilder $container)
	{
		// iikiti_mfa.auth.strategy
		dump($container->findTaggedServiceIds('iikiti_mfa.auth.strategy'));
	}
}
