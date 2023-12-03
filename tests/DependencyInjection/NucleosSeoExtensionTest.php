<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests\DependencyInjection;

use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Nucleos\SeoBundle\Action\SitemapXMLAction;
use Nucleos\SeoBundle\DependencyInjection\NucleosSeoExtension;
use Nucleos\SeoBundle\Generator\SitemapGenerator;
use Nucleos\SeoBundle\Generator\SitemapGeneratorInterface;
use Nucleos\SeoBundle\Sitemap\Definition\DefintionManager;
use Nucleos\SeoBundle\Sitemap\Definition\DefintionManagerInterface;
use Nucleos\SeoBundle\Sitemap\SitemapServiceManager;
use Nucleos\SeoBundle\Sitemap\SitemapServiceManagerInterface;
use Nucleos\SeoBundle\Sitemap\StaticSitemapService;
use Sonata\BlockBundle\SonataBlockBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class NucleosSeoExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault(): void
    {
        $this->setParameter('kernel.bundles', [
        ]);

        $this->load();

        $this->assertActions();
        $this->assertServices();
        $this->assertSitemap();

        $this->assertContainerBuilderHasParameter('nucleos_seo.sitemap.static_urls', []);
    }

    /**
     * Tests the loading of blocks.xml file.
     */
    public function testBlocksLoading(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', [
            'SonataBlockBundle' => SonataBlockBundle::class,
            'KnpMenuBundle'     => KnpMenuBundle::class,
        ]);

        $extension = new NucleosSeoExtension();
        $extension->load([[]], $container);

        self::assertTrue($container->hasDefinition('nucleos_seo.block.breadcrumb.homepage'));

        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', []);

        $extension->load([[]], $container);

        self::assertFalse($container->hasDefinition('nucleos_seo.block.breadcrumb.homepage'));
    }

    public function testLoadWithCacheService(): void
    {
        $this->setParameter('kernel.bundles', [
        ]);

        $this->load([
            'cache' => [
                'service' => 'acme.foo.service',
            ],
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(SitemapGenerator::class, 2);
    }

    public function testLoadWithStaticSitemaps(): void
    {
        $this->setParameter('kernel.bundles', [
        ]);

        $this->load([
            'sitemap' => [
                'static' => [
                    [
                        'url'        => 'http://example.com',
                        'priority'   => 100,
                        'changefreq' => 'daily',
                    ],
                    [
                        'url'        => 'http://google.com',
                        'priority'   => 50,
                        'changefreq' => 'daily',
                    ],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('nucleos_seo.sitemap.static_urls', [
            [
                'url'        => 'http://example.com',
                'priority'   => 100,
                'changefreq' => 'daily',
            ],
            [
                'url'        => 'http://google.com',
                'priority'   => 50,
                'changefreq' => 'daily',
            ],
        ]);
    }

    protected function getContainerExtensions(): array
    {
        return [
            new NucleosSeoExtension(),
        ];
    }

    private function assertActions(): void
    {
        $this->assertContainerBuilderHasService(SitemapXMLAction::class);
    }

    private function assertServices(): void
    {
        $this->assertContainerBuilderHasAlias(SitemapServiceManagerInterface::class, SitemapServiceManager::class);
        $this->assertContainerBuilderHasAlias(DefintionManagerInterface::class, DefintionManager::class);
        $this->assertContainerBuilderHasAlias(SitemapGeneratorInterface::class, SitemapGenerator::class);

        $this->assertContainerBuilderHasService(SitemapServiceManager::class);
        $this->assertContainerBuilderHasService(DefintionManager::class);
        $this->assertContainerBuilderHasService(SitemapGenerator::class);
    }

    private function assertSitemap(): void
    {
        $this->assertContainerBuilderHasService(StaticSitemapService::class);
    }
}
