<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\DependencyInjection\Compiler;

use Nucleos\SeoBundle\Seo\SeoPageInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Christian Gripp <mail@core23.de>
 */
final class ServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var array<string, mixed> $config */
        $config = $container->getParameter('nucleos_seo.config');

        $definition = $container->findDefinition($config['default']);

        $definition->addMethodCall('setTitle', [$config['title']]);
        if (null !== $config['title_prefix']) {
            $definition->addMethodCall('addTitlePrefix', [$config['title_prefix']]);
        }
        if (null !== $config['title_suffix']) {
            $definition->addMethodCall('addTitleSuffix', [$config['title_suffix']]);
        }

        $definition->addMethodCall('setMetas', [$config['metas']]);
        $definition->addMethodCall('setHtmlAttributes', [$config['head']]);
        $definition->addMethodCall('setSeparator', [$config['separator']]);

        $container->setAlias('nucleos_seo.page', $config['default'])->setPublic(true);
        $container->setAlias(SeoPageInterface::class, $config['default']);
    }
}
