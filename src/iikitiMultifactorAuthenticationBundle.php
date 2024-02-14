<?php

namespace iikiti\MfaBundle;

use Doctrine\ORM\Mapping\Entity;
use iikiti\MfaBundle\Authentication\Interface\MfaPreferencesInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class iikitiMultifactorAuthenticationBundle extends AbstractBundle
{
	public const SITE_ENTITY_KEY = 'iikiti_mfa.site_entity';

	public function configure(DefinitionConfigurator $definition): void
	{
		parent::configure($definition);
		$rootNode = $definition->rootNode();
		if (false === $rootNode instanceof ArrayNodeDefinition) {
			throw new \RuntimeException('Invalid root node type.');
		}
		$rootNode->
			children()->
				scalarNode('site_entity')->
					isRequired()->
					cannotBeEmpty()->
					info('The repository for site objects.')->
					validate()->
						ifTrue(function (mixed $value) {
							return !is_string($value) || !empty($value);
						})->
							thenInvalid(
								'Value must be a non-empty string consisting of '.
								'the full site repository\'s class name.'
							)->
						ifTrue(function (mixed $value) {
							try {
								$r = new \ReflectionClass($value);
							} catch (\ReflectionException) {
								return true;
							}

							return !$this->__isDoctrineEntity($r);
						})->
							thenInvalid(
								'Invalid class provided. Must be site entity that '.
								'implements the '.Entity::class.' attribute and '.
								MfaPreferencesInterface::class.'.'
							)->
					end();
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
		$container->parameters()->set(self::SITE_ENTITY_KEY, $config['site_entity']);
	}
}
