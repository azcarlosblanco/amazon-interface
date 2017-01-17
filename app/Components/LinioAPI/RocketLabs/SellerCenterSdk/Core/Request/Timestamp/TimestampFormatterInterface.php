<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\Timestamp;

/**
 * Interface TimestampFormatterInterface
 */
interface TimestampFormatterInterface
{

    /**
     * @param \DateTimeInterface|null $time
     * @return string
     */
    public function getFormattedTimestamp(\DateTimeInterface $time = null);
}
