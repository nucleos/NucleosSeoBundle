<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\DependencyInjection;

use Nucleos\SeoBundle\Generator\SitemapGenerator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class NucleosSeoExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('action.php');
        $loader->load('event.php');
        $loader->load('services.php');
        $loader->load('sitemap.php');

        /** @var array<string, mixed> $bundles */
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['KnpMenuBundle'])) {
            $loader->load('blocks.php');
        }

        $this->configureSeoPage($config['page'], $container);
        $this->configureCache($container, $config);
        $this->configureSitemap($container, $config);

        $container->getDefinition('nucleos_seo.twig.extension')
            ->replaceArgument(1, $config['encoding'])
        ;
    }

    /**
     * @param array<mixed> $config
     */
    private function configureSeoPage(array $config, ContainerBuilder $container): void
    {
        $container->setParameter('nucleos_seo.config', $config);
    }

    /**
     * @param array<mixed> $config
     */
    private function configureCache(ContainerBuilder $container, array $config): void
    {
        if (null === $config['cache']['service']) {
            return;
        }

        $container->getDefinition(SitemapGenerator::class)
            ->replaceArgument(2, new Reference($config['cache']['service']))
        ;
    }

    /**
     * @param array<mixed> $config
     */
    private function configureSitemap(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('nucleos_seo.sitemap.static_urls', $config['sitemap']['static']);
    }
}
