<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests\Sitemap;

use Nucleos\SeoBundle\Exception\SitemapNotFoundException;
use Nucleos\SeoBundle\Sitemap\Definition\SitemapDefinition;
use Nucleos\SeoBundle\Sitemap\Definition\SitemapDefinitionInterface;
use Nucleos\SeoBundle\Sitemap\SitemapServiceInterface;
use Nucleos\SeoBundle\Sitemap\SitemapServiceManager;
use Nucleos\SeoBundle\Tests\Fixtures\SitemapDefinitionStub;
use Nucleos\SeoBundle\Tests\Fixtures\SitemapService;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use TypeError;

final class SitemapServiceManagerTest extends TestCase
{
    public function testCreationWithInvalidServices(): void
    {
        $this->expectException(TypeError::class);

        new SitemapServiceManager([
            'invalid' => new stdClass(),
        ]);
    }

    public function testGet(): void
    {
        $definition = new SitemapDefinition('my-type', []);

        $service = new SitemapService();

        $manager = new SitemapServiceManager([
            'my-type' => $service,
        ]);
        $result =  $manager->get($definition);

        self::assertInstanceOf(SitemapServiceInterface::class, $result);
        self::assertSame([
            'custom'           => 'foo',
            'use_cache'        => true,
            'extra_cache_keys' => [],
            'ttl'              => 86400,
        ], $definition->getSettings());
    }

    public function testGetWithOverride(): void
    {
        $definition = new SitemapDefinition('my-type', [
            'custom'           => 'bar',
            'use_cache'        => false,
            'extra_cache_keys' => ['my-key'],
            'ttl'              => 0,
        ]);

        $service = new SitemapService();

        $manager = new SitemapServiceManager([
            'my-type' => $service,
        ]);
        $result =  $manager->get($definition);

        self::assertInstanceOf(SitemapServiceInterface::class, $result);
        self::assertSame([
            'use_cache'        => false,
            'extra_cache_keys' => ['my-key'],
            'ttl'              => 0,
            'custom'           => 'bar',
        ], $definition->getSettings());
    }

    public function testGetWithInvalidOverride(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessage('The option "invalid" does not exist. Defined options are: "custom", "extra_cache_keys", "ttl", "use_cache"');

        $definition = new SitemapDefinition('my-type', [
            'invalid' => 'value',
        ]);

        $service = new SitemapService();

        $manager = new SitemapServiceManager([
            'my-type' => $service,
        ]);
        $manager->get($definition);
    }

    public function testGetWithInvalidDefinition(): void
    {
        $this->expectException(SitemapNotFoundException::class);
        $this->expectExceptionMessage('The sitemap service "my-type" does not exist');

        $definition = $this->createMock(SitemapDefinitionInterface::class);
        $definition->method('getType')
            ->willReturn('my-type')
        ;
        $definition->method('getSettings')
            ->willReturn([])
        ;

        $manager = new SitemapServiceManager();
        $manager->get($definition);
    }

    public function testAddSitemap(): void
    {
        $service = $this->createMock(SitemapServiceInterface::class);

        $manager = new SitemapServiceManager();
        $manager->addSitemap('my-type', $service);

        $definition = new SitemapDefinitionStub('my-type');

        self::assertSame($service, $manager->get($definition));
    }
}
