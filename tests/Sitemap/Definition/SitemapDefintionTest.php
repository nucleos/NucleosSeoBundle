<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests\Sitemap\Definition;

use Nucleos\SeoBundle\Sitemap\Definition\SitemapDefinition;
use PHPUnit\Framework\TestCase;

final class SitemapDefintionTest extends TestCase
{
    public function testItIsInstantiable(): void
    {
        $definition = new SitemapDefinition('acme.sitemap');

        self::assertSame('acme.sitemap', $definition->getType());
        self::assertSame('acme.sitemap', $definition->toString());
        self::assertSame('acme.sitemap', $definition->__toString());
        self::assertSame([], $definition->getSettings());
    }

    public function testSetting(): void
    {
        $definition = new SitemapDefinition('acme.sitemap');
        $definition->setSettings([
            'foo'=> 'bar',
        ]);

        self::assertSame('bar', $definition->getSetting('foo'));
    }

    public function testSettingWithDefault(): void
    {
        $definition = new SitemapDefinition('acme.sitemap');

        self::assertSame('baz', $definition->getSetting('foo', 'baz'));
    }

    public function testTtl(): void
    {
        $definition = new SitemapDefinition('acme.sitemap', [
            'use_cache' => true,
            'ttl'       => 90,
        ]);

        self::assertSame(90, $definition->getTtl());
    }

    public function testTtlWithoutCache(): void
    {
        $definition = new SitemapDefinition('acme.sitemap', [
            'use_cache' => false,
        ]);

        self::assertSame(0, $definition->getTtl());
    }

    public function testTtlDefault(): void
    {
        $definition = new SitemapDefinition('acme.sitemap');

        self::assertSame(0, $definition->getTtl());
    }
}
