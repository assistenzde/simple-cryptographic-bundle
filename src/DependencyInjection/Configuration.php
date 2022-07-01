<?php

declare( strict_types=1 );

namespace Assistenzde\SimpleCryptographicBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Prokki\Htpasswd
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('simple-cryptographic-bundle');

        // @formatter:off
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('key')
                    ->info('The key to use for all cipher encrypt/decrypt calls. Skip the configuration to use the value of the %kernel.secret% parameter or pass a custom key.')
                    ->example('My-$ecr3t-k3y')
                ->end()
                ->scalarNode('cipher')
                    ->info('The cipher method to use for all cipher encrypt/decrypt calls - {@see openssl_get_cipher_methods()}. Skip the configuration to use the default "aes-256-ctr" method.')
                    ->example('des-cbc')
                ->end()
            ->end();
        // @formatter:on

        return $treeBuilder;
    }
}
