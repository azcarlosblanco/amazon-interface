<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Order\Model;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Model\ArrayCollection;

/**
 * Class StatusCollection
 */
class StatusCollection extends ArrayCollection
{
    const STATUS_KEY = 'Status';

    /**
     * @param string|string[] $statuses
     */
    public function __construct(array $statuses)
    {
        $statuses = isset($statuses[static::STATUS_KEY]) ? $statuses[static::STATUS_KEY] : [];

        if (!is_array($statuses)) {
            $statuses = [$statuses];
        }

        parent::__construct($statuses);
    }
}
