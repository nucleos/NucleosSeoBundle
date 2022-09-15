<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests\Action;

use Nucleos\SeoBundle\Action\SitemapXMLAction;
use Nucleos\SeoBundle\Generator\SitemapGeneratorInterface;
use PHPUnit\Framework\TestCase;

final class SitemapXMLActionTest extends TestCase
{
    public function testExecute(): void
    {
        $generator = $this->createMock(SitemapGeneratorInterface::class);
        $generator->method('toXML')
            ->willReturn('<xml></xml>')
        ;

        $action = new SitemapXMLAction($generator);

        $response = $action();

        static::assertSame('text/xml', $response->headers->get('Content-Type'));
        static::assertSame('<xml></xml>', $response->getContent());
    }
}
