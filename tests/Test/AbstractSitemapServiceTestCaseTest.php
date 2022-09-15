<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests\Test;

use DateTime;
use Nucleos\SeoBundle\Model\Url;
use Nucleos\SeoBundle\Sitemap\Definition\SitemapDefinitionInterface;
use Nucleos\SeoBundle\Sitemap\SitemapServiceInterface;
use Nucleos\SeoBundle\Test\AbstractSitemapServiceTestCase as ParentTestCase;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\MockObject\MockObject;

final class AbstractSitemapServiceTestCaseTest extends ParentTestCase
{
    /**
     * @var MockObject&SitemapServiceInterface
     */
    private SitemapServiceInterface $serviceMock;

    protected function setUp(): void
    {
        $this->serviceMock = $this->createMock(SitemapServiceInterface::class);

        parent::setUp();
    }

    public function testAssertSitemapCount(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that actual size 2 matches expected size 1.');

        $sitemap = $this->createMock(SitemapDefinitionInterface::class);

        $this->serviceMock->method('execute')->with($sitemap)
            ->willReturn([
                new Url('/path/foo', 20, Url::FREQUENCE_DAILY),
                new Url('/path/bar', 20, Url::FREQUENCE_DAILY),
            ])
        ;

        $this->assertSitemap('/path/bar', 20, Url::FREQUENCE_DAILY);

        $this->process($sitemap);
    }

    public function testAssertUrlNotCalled(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage("The url '/path/foo' was not expected to be called.");

        $sitemap = $this->createMock(SitemapDefinitionInterface::class);

        $this->serviceMock->method('execute')->with($sitemap)
            ->willReturn(
                [
                    new Url('/path/foo', 20, Url::FREQUENCE_DAILY),
                ]
            )
        ;

        $this->assertSitemap('/path/bar', 20, Url::FREQUENCE_DAILY);

        $this->process($sitemap);
    }

    public function testAssertLastmod(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage("The url '/path/foo' was expected with a different lastmod.");

        $sitemap = $this->createMock(SitemapDefinitionInterface::class);

        $this->serviceMock->method('execute')->with($sitemap)
            ->willReturn([
                new Url('/path/foo', 20, Url::FREQUENCE_DAILY, new DateTime('2018-10-02')),
            ])
        ;

        $this->assertSitemap('/path/foo', 20, Url::FREQUENCE_DAILY, new DateTime('2018-10-01'));

        $this->process($sitemap);
    }

    public function testAssertPriority(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage("The url '/path/foo' was expected with 20 priority. 60 given.");

        $sitemap = $this->createMock(SitemapDefinitionInterface::class);

        $this->serviceMock->method('execute')->with($sitemap)
            ->willReturn([
                new Url('/path/foo', 60, Url::FREQUENCE_DAILY),
            ])
        ;

        $this->assertSitemap('/path/foo', 20, Url::FREQUENCE_DAILY);

        $this->process($sitemap);
    }

    public function testAssertChangeFreq(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage("The url '/path/foo' was expected with weekly changefreq. daily given.");

        $sitemap = $this->createMock(SitemapDefinitionInterface::class);

        $this->serviceMock->method('execute')->with($sitemap)
            ->willReturn([
                new Url('/path/foo', 20, Url::FREQUENCE_DAILY),
            ])
        ;

        $this->assertSitemap('/path/foo', 20, Url::FREQUENCE_WEEKLY);

        $this->process($sitemap);
    }

    protected function createService(): SitemapServiceInterface
    {
        return $this->serviceMock;
    }
}
