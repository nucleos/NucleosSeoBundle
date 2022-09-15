<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle;

use Nucleos\SeoBundle\DependencyInjection\Compiler\BreadcrumbBlockServicesCompilerPass;
use Nucleos\SeoBundle\DependencyInjection\Compiler\ServiceCompilerPass;
use Nucleos\SeoBundle\DependencyInjection\Compiler\SitemapCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class NucleosSeoBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new BreadcrumbBlockServicesCompilerPass());
        $container->addCompilerPass(new ServiceCompilerPass());
        $container->addCompilerPass(new SitemapCompilerPass());
    }
}
