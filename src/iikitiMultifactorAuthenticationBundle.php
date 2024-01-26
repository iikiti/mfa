<?php

namespace iikiti\MfaBundle;

use Doctrine\Persistence\ObjectRepository;
use iikiti\MfaBundle\Authentication\Interface\MfaPreferencesInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class iikitiMultifactorAuthenticationBundle extends AbstractBundle
{
	public const SITE_REPOSITORY_KEY = 'iikiti_mfa.site_repository';

	public function configure(DefinitionConfigurator $definition): void
	{
		parent::configure($definition);
		$definition->rootNode()->
			children()->
				scalarNode('site_repository')->
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

							return !$r->implementsInterface(ObjectRepository::class) ||
								!$r->implementsInterface(MfaPreferencesInterface::class);
						})->
							thenInvalid(
								'Invalid class provided. Must be site repository that '.
								'implements '.ObjectRepository::class.' and '.
								MfaPreferencesInterface::class.'.'
							)->
					end()->
				end()->
			end();
	}

	public function loadExtension(
		array $config,
		ContainerConfigurator $container,
		ContainerBuilder $builder
	): void {
		parent::loadExtension($config, $container, $builder);
		$container->import('../config/services.yaml');
		$container->parameters()->set(self::SITE_REPOSITORY_KEY, $config['site_repository']);
	}
}
