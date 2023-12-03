<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests\Seo;

use Nucleos\SeoBundle\Seo\SeoPage;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class SeoPageTest extends TestCase
{
    public function testAddMeta(): void
    {
        $page = new SeoPage();
        $page->addMeta('property', 'foo', 'bar');

        $expected = [
            'http-equiv' => [],
            'name'       => [],
            'schema'     => [],
            'charset'    => [],
            'property'   => ['foo' => ['bar', []]],
        ];

        self::assertSame($expected, $page->getMetas());
    }

    public function testOverrideMetas(): void
    {
        $page = new SeoPage();
        $page->setMetas(['property' => ['foo' => 'bar', 'foo2' => ['bar2', []]]]);

        $expected = [
            'property' => ['foo' => ['bar', []], 'foo2' => ['bar2', []]],
        ];

        self::assertSame($expected, $page->getMetas());
    }

    public function testRemoveMeta(): void
    {
        $page = new SeoPage();
        $page->setMetas(['property' => ['foo' => 'bar', 'foo2' => ['bar2', []]]]);
        $page->removeMeta('property', 'foo');

        self::assertFalse($page->hasMeta('property', 'foo'));
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function testInvalidMetas(): void
    {
        $this->expectException(RuntimeException::class);

        $page = new SeoPage();
        // @phpstan-ignore-next-line
        $page->setMetas([
            'type' => 'not an array',
        ]);
    }

    public function testHtmlAttributes(): void
    {
        $page = new SeoPage();
        $page->setHtmlAttributes(['key1' => 'value1']);
        $page->addHtmlAttributes('key2', 'value2');

        $expected = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        self::assertSame($expected, $page->getHtmlAttributes());

        self::assertTrue($page->hasHtmlAttribute('key2'));
        $page->removeHtmlAttributes('key2');
        self::assertFalse($page->hasHtmlAttribute('key2'));
    }

    public function testHeadAttributes(): void
    {
        $page = new SeoPage();
        $page->setHeadAttributes(['head1' => 'value1']);
        $page->addHeadAttribute('head2', 'value2');

        $expected = [
            'head1' => 'value1',
            'head2' => 'value2',
        ];

        self::assertSame($expected, $page->getHeadAttributes());

        self::assertTrue($page->hasHeadAttribute('head1'));
        $page->removeHeadAttribute('head1');
        self::assertFalse($page->hasHeadAttribute('head1'));
    }

    public function testSetTitle(): void
    {
        $page = new SeoPage();
        $page->setTitle('My title');

        self::assertSame('My title', $page->getTitle());
    }

    public function addTitlePrefix(): void
    {
        $page = new SeoPage();
        $page->setTitle('My title');
        $page->setSeparator(' - ');
        $page->addTitlePrefix('Additional title');

        self::assertSame('Additional title - My title', $page->getTitle());
        self::assertSame('My title', $page->getOriginalTitle());
    }

    public function addTitleSuffix(): void
    {
        $page = new SeoPage();
        $page->setTitle('My title');
        $page->setSeparator(' - ');
        $page->addTitleSuffix('Additional title');

        self::assertSame('My title - Additional title', $page->getTitle());
        self::assertSame('My title', $page->getOriginalTitle());
    }

    public function testLinkCanonical(): void
    {
        $page = new SeoPage();
        $page->setLinkCanonical('http://example.com');

        self::assertSame('http://example.com', $page->getLinkCanonical());

        $page->removeLinkCanonical();
        self::assertSame('', $page->getLinkCanonical());
    }

    public function testLangAlternates(): void
    {
        $page = new SeoPage();
        $page->setLangAlternates(['http://example.com/' => 'x-default']);
        $page->addLangAlternate('http://example.com/en-us', 'en-us');

        $expected = [
            'http://example.com/'      => 'x-default',
            'http://example.com/en-us' => 'en-us',
        ];

        self::assertSame($expected, $page->getLangAlternates());

        self::assertTrue($page->hasLangAlternate('http://example.com/'));
        $page->removeLangAlternate('http://example.com/');
        self::assertFalse($page->hasLangAlternate('http://example.com/'));
    }

    /**
     * The hasMeta() should return true for a defined meta, false otherwise.
     */
    public function testHasMeta(): void
    {
        $page = new SeoPage();
        $page->addMeta('property', 'test', '');

        self::assertTrue($page->hasMeta('property', 'test'));
        self::assertFalse($page->hasMeta('property', 'fake'));
    }
}
