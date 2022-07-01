<?php

namespace Assistenzde\SimpleCryptographicBundle;

use Assistenzde\SimpleCryptographicBundle\DependencyInjection\SimpleCryptographicBundleExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class SimpleCryptographicBundle
 *
 * @package Assistenzde\SimpleCryptographicBundle
 */
class SimpleCryptographicBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new SimpleCryptographicBundleExtension();
    }
}
