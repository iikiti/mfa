<?php

namespace iikiti\MfaBundle;

use iikiti\MfaBundle\Compiler\StrategyCompiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class iikitiMultifactorAuthenticationBundle extends AbstractBundle
{
	public function loadExtension(
		array $config,
		ContainerConfigurator $container,
		ContainerBuilder $builder
	): void {
		// load an XML, PHP or Yaml file
		$container->import('../config/services.yaml');
	}

	public function build(ContainerBuilder $container): void
	{
		parent::build($container);

		$container->addCompilerPass(new StrategyCompiler());
	}
}
