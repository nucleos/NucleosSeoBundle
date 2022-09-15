<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 */
final class NucleosSeoExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);
        $config        = $config;

        /** @var array<string, mixed> $bundles */
        $bundles = $container->getParameter('kernel.bundles');

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (isset($bundles['SonataBlockBundle'], $bundles['KnpMenuBundle'])) {
            $loader->load('blocks.php');
        }

        $loader->load('event.php');
        $loader->load('services.php');

        $this->configureSeoPage($config['page'], $container);

        $container->getDefinition('nucleos_seo.twig.extension')
            ->replaceArgument(1, $config['encoding'])
        ;
    }

    /**
     * Configure the default seo page.
     *
     * @param mixed[] $config
     */
    private function configureSeoPage(array $config, ContainerBuilder $container): void
    {
        $container->setParameter('nucleos_seo.config', $config);
    }
}
