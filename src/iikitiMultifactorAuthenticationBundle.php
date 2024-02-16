<?php

namespace iikiti\MfaBundle;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class iikitiMultifactorAuthenticationBundle extends AbstractBundle
{
	public function configure(DefinitionConfigurator $definition): void
	{
		parent::configure($definition);
	}

	private function __isDoctrineEntity(\ReflectionClass $reflectionClass): bool
	{
		if (
			!empty($reflectionClass->getAttributes(Entity::class, \ReflectionAttribute::IS_INSTANCEOF))
		) {
			return true;
		}

		return false;
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
