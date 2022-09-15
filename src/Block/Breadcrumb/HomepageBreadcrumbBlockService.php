<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Block\Breadcrumb;

/**
 * BlockService for homepage breadcrumb.
 *
 * @author Sylvain Deloux <sylvain.deloux@ekino.com>
 */
final class HomepageBreadcrumbBlockService extends BaseBreadcrumbMenuBlockService
{
    public function handleContext(string $context): bool
    {
        return 'homepage' === $context;
    }
}
