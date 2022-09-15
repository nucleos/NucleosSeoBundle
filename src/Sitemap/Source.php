<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Sitemap;

use Iterator;
use Sonata\Exporter\Source\ChainSourceIterator;

final class Source
{
    /**
     * @var mixed[]
     */
    private array $types = [];

    private ChainSourceIterator $sources;

    public function __construct()
    {
        $this->sources = new ChainSourceIterator();
    }

    public function getSources(): ChainSourceIterator
    {
        return $this->sources;
    }

    public function addSource(Iterator $source): void
    {
        $this->sources->addSource($source);
    }

    /**
     * @return mixed[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * @param mixed[] $types
     */
    public function addTypes(array $types): void
    {
        $this->types += array_diff($types, $this->types);
    }
}
