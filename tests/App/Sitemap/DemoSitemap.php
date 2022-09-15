<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests\App\Sitemap;

use Nucleos\SeoBundle\Model\Url;
use Nucleos\SeoBundle\Sitemap\Definition\SitemapDefinitionInterface;
use Nucleos\SeoBundle\Sitemap\SitemapServiceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DemoSitemap implements SitemapServiceInterface
{
    public function configureSettings(OptionsResolver $resolver): void
    {
    }

    public function execute(SitemapDefinitionInterface $sitemap): array
    {
        return [
            new Url('example.com'),
        ];
    }
}
