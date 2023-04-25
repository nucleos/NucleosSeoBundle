<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\SeoBundle\Action\SitemapXMLAction;
use Nucleos\SeoBundle\Generator\SitemapGenerator;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set(SitemapXMLAction::class)
            ->public()
            ->args([
                new Reference(SitemapGenerator::class),
            ])
    ;
};
