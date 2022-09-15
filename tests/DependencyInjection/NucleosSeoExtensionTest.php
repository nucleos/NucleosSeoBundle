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
use Nucleos\SeoBundle\DependencyInjection\NucleosSeoExtension;
use PHPUnit\Framework\TestCase;
use Sonata\BlockBundle\SonataBlockBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Vincent Tommasi <tommasi.v@gmail.com>
 */
final class NucleosSeoExtensionTest extends TestCase
{
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

        static::assertTrue($container->hasDefinition('nucleos_seo.block.breadcrumb.homepage'));

        $container = new ContainerBuilder();
        $container->setParameter('kernel.bundles', []);
        $extension->load([[]], $container);

        static::assertFalse($container->hasDefinition('nucleos_seo.block.breadcrumb.homepage'));
    }
}
