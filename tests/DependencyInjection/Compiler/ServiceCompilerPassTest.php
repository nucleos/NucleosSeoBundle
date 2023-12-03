<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests\DependencyInjection\Compiler;

use Nucleos\SeoBundle\DependencyInjection\Compiler\ServiceCompilerPass;
use Nucleos\SeoBundle\DependencyInjection\NucleosSeoExtension;
use Nucleos\SeoBundle\Seo\SeoPage;
use Nucleos\SeoBundle\Seo\SeoPageInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ServiceCompilerPassTest extends TestCase
{
    public function testServicesExistsAndCanBeOverridden(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', []);

        $container->register('nucleos_seo.custom.page', SeoPage::class);

        $config = [
            'page' => [
                'default' => 'nucleos_seo.custom.page',
            ],
        ];

        $extension = new NucleosSeoExtension();
        $extension->load([$config], $container);

        (new ServiceCompilerPass())->process($container);

        self::assertTrue($container->has('nucleos_seo.page'));
        self::assertTrue($container->has(SeoPageInterface::class));
        self::assertSame($container->get('nucleos_seo.page'), $container->get(SeoPageInterface::class));

        self::assertInstanceOf(SeoPage::class, $container->get(SeoPageInterface::class));
    }

    public function testGlobalTitle(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', []);
        $container->register('nucleos_seo.custom.page', SeoPage::class);

        $config = [
            'page' => [
                'default'      => 'nucleos_seo.custom.page',
                'title'        => 'Project name',
                'title_prefix' => 'Prefix',
                'title_suffix' => 'Suffix',
            ],
        ];

        $extension = new NucleosSeoExtension();
        $extension->load([$config], $container);

        (new ServiceCompilerPass())->process($container);

        $page = $container->get('nucleos_seo.custom.page');

        \assert($page instanceof SeoPageInterface);

        self::assertSame('Project name', $page->getOriginalTitle());
        self::assertSame('Prefix Project name Suffix', $page->getTitle());
    }
}
