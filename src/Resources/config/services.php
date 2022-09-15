<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\SeoBundle\Generator\SitemapGenerator;
use Nucleos\SeoBundle\Generator\SitemapGeneratorInterface;
use Nucleos\SeoBundle\Seo\SeoPage;
use Nucleos\SeoBundle\Sitemap\Definition\DefintionManager;
use Nucleos\SeoBundle\Sitemap\Definition\DefintionManagerInterface;
use Nucleos\SeoBundle\Sitemap\SitemapServiceManager;
use Nucleos\SeoBundle\Sitemap\SitemapServiceManagerInterface;
use Nucleos\SeoBundle\Twig\Extension\SeoExtension;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('nucleos_seo.page.default', SeoPage::class)
            ->public()

        ->set('nucleos_seo.twig.extension', SeoExtension::class)
            ->tag('twig.extension')
            ->args([
                new ReferenceConfigurator('nucleos_seo.page'),
                '',
            ])

        ->alias(SitemapServiceManagerInterface::class, SitemapServiceManager::class)
            ->public()

        ->alias(DefintionManagerInterface::class, DefintionManager::class)
            ->public()

        ->alias(SitemapGeneratorInterface::class, SitemapGenerator::class)

        ->set(SitemapServiceManager::class)

        ->set(DefintionManager::class)

        ->set(SitemapGenerator::class)
            ->args([
                new Reference(SitemapServiceManagerInterface::class),
                new Reference(DefintionManagerInterface::class),
                null,
            ])

    ;
};
