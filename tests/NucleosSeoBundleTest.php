<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests;

use Nucleos\SeoBundle\DependencyInjection\NucleosSeoExtension;
use Nucleos\SeoBundle\NucleosSeoBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class NucleosSeoBundleTest extends TestCase
{
    public function testGetContainerExtension(): void
    {
        $bundle = new NucleosSeoBundle();

        self::assertInstanceOf(NucleosSeoExtension::class, $bundle->getContainerExtension());
    }

    public function testBuild(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->expects(self::exactly(3))->method('addCompilerPass');

        $bundle = new NucleosSeoBundle();
        $bundle->build($containerBuilder);
    }
}
