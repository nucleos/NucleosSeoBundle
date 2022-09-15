<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Nucleos\SeoBundle\Command\SitemapGeneratorCommand;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // Use "service" function for creating references to services when dropping support for Symfony 4.4
    // Use "param" function for creating references to parameters when dropping support for Symfony 5.1
    $containerConfigurator->services()

        ->set(SitemapGeneratorCommand::class)
            ->tag('console.command')
            ->args([
                new ReferenceConfigurator('router'),
                new ReferenceConfigurator('nucleos_seo.sitemap.manager'),
                new ReferenceConfigurator('filesystem'),
            ])
    ;
};
