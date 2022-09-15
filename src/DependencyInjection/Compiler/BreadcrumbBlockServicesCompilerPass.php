<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\DependencyInjection\Compiler;

use SplPriorityQueue;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Sylvain Deloux <sylvain.deloux@ekino.com>
 */
final class BreadcrumbBlockServicesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $queue = new SplPriorityQueue();

        $order = PHP_INT_MAX;
        foreach ($container->findTaggedServiceIds('sonata.breadcrumb') as $id => $attributes) {
            $priority = $attributes[0]['priority'] ?? 0;

            $queue->insert(new Reference($id), [$priority, --$order]);
        }

        $definition = $container->getDefinition('nucleos_seo.event.breadcrumb');
        foreach ($queue as $serviceId) {
            $definition->addMethodCall('addBlockService', [$serviceId->__toString(), $serviceId]);
        }
    }
}
