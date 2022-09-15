<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests;

use Nucleos\SeoBundle\DependencyInjection\Compiler\BreadcrumbBlockServicesCompilerPass;
use Nucleos\SeoBundle\DependencyInjection\Compiler\ServiceCompilerPass;
use Nucleos\SeoBundle\DependencyInjection\Compiler\SitemapCompilerPass;
use Nucleos\SeoBundle\DependencyInjection\NucleosSeoExtension;
use Nucleos\SeoBundle\NucleosSeoBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class NucleosSeoBundleTest extends TestCase
{
    public function testGetContainerExtension(): void
    {
        $bundle = new NucleosSeoBundle();

        static::assertInstanceOf(NucleosSeoExtension::class, $bundle->getContainerExtension());
    }

    public function testBuild(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $containerBuilder->expects(static::exactly(3))->method('addCompilerPass')

            ->withConsecutive(
                [static::isInstanceOf(BreadcrumbBlockServicesCompilerPass::class)],
                [static::isInstanceOf(ServiceCompilerPass::class)],
                [static::isInstanceOf(SitemapCompilerPass::class)]
            )
        ;

        $bundle = new NucleosSeoBundle();
        $bundle->build($containerBuilder);
    }
}
