<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Tests\Fixtures;

use Nucleos\SeoBundle\Sitemap\Definition\SitemapDefinitionInterface;

final class SitemapDefinitionStub implements SitemapDefinitionInterface
{
    private string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTtl(): int
    {
        return 42;
    }

    public function setSettings(array $settings = []): void {}

    public function getSettings(): array
    {
        return [];
    }

    public function getSetting(string $name, $default = null)
    {
        return $default;
    }
}
