<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests\DependencyInjection\Compiler;

use Nucleos\SeoBundle\DependencyInjection\Compiler\BreadcrumbBlockServicesCompilerPass;
use Nucleos\SeoBundle\Event\BreadcrumbListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class BreadcrumbBlockServicesCompilerPassTest extends TestCase
{
    private ContainerBuilder $containerBuilder;

    private Definition $listener;

    private BreadcrumbBlockServicesCompilerPass $compilerPass;

    protected function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();

        $this->listener = $this->containerBuilder->register('nucleos_seo.event.breadcrumb', BreadcrumbListener::class);

        $this->compilerPass = new BreadcrumbBlockServicesCompilerPass();
    }

    public function testProcess(): void
    {
        $this->containerBuilder->register('acme.service');
        $this->containerBuilder->register('acme.breadcrumb')
            ->addTag('sonata.breadcrumb')
        ;

        $this->process();

        $this->assertServiceOrder([
            'acme.breadcrumb',
        ]);
    }

    public function testProcessWithStandardPriority(): void
    {
        $this->containerBuilder->register('acme.breadcrumbA')
            ->addTag('sonata.breadcrumb')
        ;
        $this->containerBuilder->register('acme.breadcrumbB')
            ->addTag('sonata.breadcrumb')
        ;
        $this->containerBuilder->register('acme.breadcrumbC')
            ->addTag('sonata.breadcrumb')
        ;
        $this->containerBuilder->register('acme.breadcrumbD')
            ->addTag('sonata.breadcrumb')
        ;

        $this->process();

        $this->assertServiceOrder([
            'acme.breadcrumbA',
            'acme.breadcrumbB',
            'acme.breadcrumbC',
            'acme.breadcrumbD',
        ]);
    }

    public function testProcessWithCustomPriority(): void
    {
        $this->containerBuilder->register('acme.breadcrumbA')
            ->addTag('sonata.breadcrumb', ['priority' => -128])
        ;
        $this->containerBuilder->register('acme.breadcrumbB')
            ->addTag('sonata.breadcrumb', ['priority' => 0])
        ;
        $this->containerBuilder->register('acme.breadcrumbC')
            ->addTag('sonata.breadcrumb', ['priority' => 128])
        ;
        $this->containerBuilder->register('acme.breadcrumbD')
            ->addTag('sonata.breadcrumb', ['priority' => -64])
        ;

        $this->process();

        $this->assertServiceOrder([
            'acme.breadcrumbC',
            'acme.breadcrumbB',
            'acme.breadcrumbD',
            'acme.breadcrumbA',
        ]);
    }

    /**
     * @return string[]
     */
    private function getCalledServices(): array
    {
        $methodCalls = $this->listener->getMethodCalls();

        return array_map(static fn ($call) => $call[1][0], $methodCalls);
    }

    private function process(): void
    {
        $this->compilerPass->process($this->containerBuilder);
    }

    /**
     * @param string[] $services
     */
    private function assertServiceOrder(array $services): void
    {
        static::assertSame($services, $this->getCalledServices());
    }
}
