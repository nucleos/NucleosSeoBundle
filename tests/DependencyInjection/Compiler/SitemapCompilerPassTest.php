<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests\DependencyInjection\Compiler;

use Nucleos\SeoBundle\DependencyInjection\Compiler\SitemapCompilerPass;
use Nucleos\SeoBundle\Sitemap\Definition\DefintionManagerInterface;
use Nucleos\SeoBundle\Sitemap\SitemapServiceManagerInterface;
use Nucleos\SeoBundle\Sitemap\StaticSitemapService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class SitemapCompilerPassTest extends TestCase
{
    /**
     * @var Definition&MockObject
     */
    private $serviceManager;

    /**
     * @var Definition&MockObject
     */
    private $definitionManager;

    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp(): void
    {
        $this->serviceManager = $this->createMock(Definition::class);
        $this->serviceManager->method('hasTag')->with('nucleos_seo.sitemap')
            ->willReturn(false)
        ;
        $this->definitionManager = $this->createMock(Definition::class);
        $this->definitionManager->method('hasTag')->with('nucleos_seo.sitemap')
            ->willReturn(false)
        ;

        $this->container = new ContainerBuilder();
        $this->container->setDefinition(SitemapServiceManagerInterface::class, $this->serviceManager);
        $this->container->setDefinition(DefintionManagerInterface::class, $this->definitionManager);
    }

    public function testProcess(): void
    {
        $this->serviceManager->expects(static::once())->method('addMethodCall')
            ->with('addSitemap', static::callback(static function (array $args): bool {
                return 'acme.sitemap' === $args[0] && $args[1] instanceof Reference;
            }))
        ;

        $this->definitionManager->expects(static::once())->method('addMethodCall')->with('addDefinition', [
            'acme.sitemap',
        ])
        ;

        $sitemapDefinition = new Definition();
        $sitemapDefinition->addTag('nucleos_seo.sitemap');

        $this->container->setParameter('nucleos_seo.sitemap.static_urls', []);
        $this->container->setDefinition('acme.sitemap', $sitemapDefinition);

        $compiler = new SitemapCompilerPass();
        $compiler->process($this->container);

        static::assertTrue($sitemapDefinition->isPublic());

        $this->definitionManager->expects(static::never())->method('addMethodCall');
    }

    public function testProcessWithNoServices(): void
    {
        $this->container->setParameter('nucleos_seo.sitemap.static_urls', []);

        $compiler = new SitemapCompilerPass();
        $compiler->process($this->container);

        static::assertSame([], $this->container->getParameter('nucleos_seo.sitemap.static_urls'));

        $this->definitionManager->expects(static::never())->method('addMethodCall');
    }

    public function testProcessWithStaticUrls(): void
    {
        $this->definitionManager->expects(static::once())->method('addMethodCall')->with('addDefinition', [
            StaticSitemapService::class,
            [
                [
                    'url'        => 'http://example.com',
                    'priority'   => 100,
                    'changefreq' => 'daily',
                ],
            ],
        ])
        ;

        $this->container->setParameter('nucleos_seo.sitemap.static_urls', [
            'static' => [
                [
                    'url'        => 'http://example.com',
                    'priority'   => 100,
                    'changefreq' => 'daily',
                ],
            ],
        ]);

        $compiler = new SitemapCompilerPass();
        $compiler->process($this->container);
    }

    public function testProcessWithEmptyGroups(): void
    {
        $this->container->setParameter('nucleos_seo.sitemap.static_urls', []);

        $compiler = new SitemapCompilerPass();
        $compiler->process($this->container);

        $this->serviceManager->expects(static::never())->method('addMethodCall');
        $this->definitionManager->expects(static::never())->method('addMethodCall');
    }
}
