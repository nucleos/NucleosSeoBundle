<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SeoBundle\Model;

use DateTime;

final class Url implements UrlInterface
{
    /**
     * Always visit url.
     */
    public const FREQUENCE_ALWAYS = 'always';

    /**
     * Visit url every hour.
     */
    public const FREQUENCE_HOURLY = 'hourly';

    /**
     * Visit url every day.
     */
    public const FREQUENCE_DAILY = 'daily';

    /**
     * Visit url every week.
     */
    public const FREQUENCE_WEEKLY = 'weekly';

    /**
     * Visit url every month.
     */
    public const FREQUENCE_MONTHLY = 'monthly';

    /**
     * Visit url every year.
     */
    public const FREQUENCE_YEARLY = 'yearly';

    /**
     * Never visit url again.
     */
    public const FREQUENCE_NEVER = 'never';

    private string $loc;

    private ?DateTime $lastMod = null;

    private ?string $changeFreq = null;

    private ?int $priority = null;

    public function __construct(string $loc, ?int $priority = null, ?string $changeFreq = null, ?DateTime $lastMod = null)
    {
        $this->loc        = $loc;
        $this->lastMod    = $lastMod;
        $this->changeFreq = $changeFreq;
        $this->priority   = $priority;
    }

    public function getChangeFreq(): ?string
    {
        return $this->changeFreq;
    }

    public function getLastMod(): ?DateTime
    {
        return $this->lastMod;
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }
}
