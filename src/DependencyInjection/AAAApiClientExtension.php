<?php

namespace VerisureLab\Library\AAAApiClient\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use VerisureLab\Library\AAAApiClient\Service\AuthenticationService;
use VerisureLab\Library\AAAApiClient\Service\Client;

class AAAApiClientExtension extends ConfigurableExtension implements CompilerPassInterface
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->setParameter('verisure_lab.aaa_api_client.connections', $mergedConfig['connections']);
    }

    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getParameter('verisure_lab.aaa_api_client.connections') as $connectionName => $settings) {
            $clientName = 'verisure_lab.aaa_api_client.client.'.$connectionName;

            $clientDefinition = $container->register($clientName, Client::class);
            $clientDefinition
                ->addArgument($settings['client_id'])
                ->addArgument($settings['client_secret'])
                ->addArgument($settings['base_uri']);

            $authenticationServiceName = 'verisure_lab.aaa_api_client.authentication_service.'.$connectionName;

            $authenticationServiceDefinition = $container->register($authenticationServiceName, AuthenticationService::class);
            $authenticationServiceDefinition
                ->addArgument(new Reference($clientName));
        }
    }
}