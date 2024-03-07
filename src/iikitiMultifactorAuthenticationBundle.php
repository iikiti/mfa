<?php

namespace iikiti\MfaBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Bundle class to handle multi-factor authentication. Loads configuration.
 */
class iikitiMultifactorAuthenticationBundle extends AbstractBundle
{
	public function configure(DefinitionConfigurator $definition): void
	{
		parent::configure($definition);
	}

	public function loadExtension(
		array $config,
		ContainerConfigurator $container,
		ContainerBuilder $builder
	): void {
		parent::loadExtension($config, $container, $builder);
		$container->import('../config/services.yaml');
	}
}
