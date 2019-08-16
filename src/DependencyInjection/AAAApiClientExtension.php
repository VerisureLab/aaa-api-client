<?php

namespace VerisureLab\Library\AAAApiClient\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use VerisureLab\Library\AAAApiClient\Service\AuthenticationService;
use VerisureLab\Library\AAAApiClient\Service\TokenClient;

class AAAApiClientExtension extends ConfigurableExtension implements CompilerPassInterface
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->setParameter('verisure_lab.aaa_api_client.connections', $mergedConfig['connections']);
    }

    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getParameter('verisure_lab.aaa_api_client.connections') as $connectionName => $settings) {
            $tokenClientName = 'verisure_lab.aaa_api_client.token_client.'.$connectionName;

            $tokenClientDefinition = $container->register($tokenClientName, TokenClient::class);
            $tokenClientDefinition
                ->addArgument($settings['client_id'])
                ->addArgument($settings['client_secret'])
                ->addArgument($settings['base_uri']);

            $apiClientName = 'verisure_lab.aaa_api_client.api_client.'.$connectionName;

            $apiClientDefinition = $container->register($apiClientName, TokenClient::class);
            $apiClientDefinition
                ->addArgument($settings['base_uri']);

            $authenticationServiceName = 'verisure_lab.aaa_api_client.authentication_service.'.$connectionName;

            $authenticationServiceDefinition = $container->register($authenticationServiceName, AuthenticationService::class);
            $authenticationServiceDefinition
                ->addArgument(new Reference($tokenClientName));
        }
    }
}