<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Seo;

use Symfony\Contracts\Service\Attribute\Required;

trait SeoAwareTrait
{
    #[Required]
    protected ?SeoPageInterface $seoPage = null;

    public function setSeoPage(?SeoPageInterface $seoPage = null): void
    {
        $this->seoPage = $seoPage;
    }
}
