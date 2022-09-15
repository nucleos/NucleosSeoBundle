<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\DependencyInjection\Compiler;

use Nucleos\SeoBundle\Sitemap\Definition\DefintionManagerInterface;
use Nucleos\SeoBundle\Sitemap\SitemapServiceManagerInterface;
use Nucleos\SeoBundle\Sitemap\StaticSitemapService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class SitemapCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $serviceManager    = $container->findDefinition(SitemapServiceManagerInterface::class);
        $definitionManager = $container->findDefinition(DefintionManagerInterface::class);

        foreach ($container->findTaggedServiceIds('nucleos_seo.sitemap') as $id => $attributes) {
            $definition = $container->getDefinition($id);
            $definition->setPublic(true);

            $serviceManager->addMethodCall('addSitemap', [
                $id,
                new Reference($id),
            ]);

            $definitionManager->addMethodCall('addDefinition', [
                $id,
            ]);
        }

        $this->addStaticUrls($container, $definitionManager);
    }

    private function addStaticUrls(ContainerBuilder $container, Definition $definitionManager): void
    {
        foreach ($container->getParameter('nucleos_seo.sitemap.static_urls') as $options) {
            $definitionManager->addMethodCall('addDefinition', [
                StaticSitemapService::class, $options,
            ]);
        }
    }
}
