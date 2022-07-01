<?php

declare( strict_types=1 );

namespace Assistenzde\SimpleCryptographicBundle\DependencyInjection;

use Assistenzde\SimpleCryptographicBundle\Service\SimpleCryptographicService;
use Exception;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class SimpleCryptographicBundleExtension
 *
 * @link    http://symfony.com/doc/current/bundles/extension.html
 *
 * @package Assistenzde\SimpleCryptographicBundle
 */
class SimpleCryptographicBundleExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function getAlias(): string
    {
        return 'simple-cryptographic-bundle';
    }

    /**
     * {@inheritDoc}
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // register the only service
        $serviceDefinition = $container->register(SimpleCryptographicService::class);

        // get the configuration
        $configuration = $this->getConfiguration($configs, $container);
        $config        = $this->processConfiguration($configuration, $configs);

        // and process cipher key and cipher method
        if( !array_key_exists('key', $config) || empty($config[ 'key' ]) )
        {
            $config[ 'key' ] = $container->getParameter('kernel.secret');
        }
        if( !array_key_exists('cipher', $config) )
        {
            $config[ 'cipher' ] = null;
        }

        // add config values to service constructor
        $serviceDefinition->setArgument(0, $config[ 'key' ]);
        $serviceDefinition->setArgument(1, $config[ 'cipher' ]);
    }
}
