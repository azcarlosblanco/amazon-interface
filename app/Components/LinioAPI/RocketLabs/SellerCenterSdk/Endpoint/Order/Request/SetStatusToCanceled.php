<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Order\Request;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\GenericRequest;

/**
 * Class SetStatusToCanceled
 */
class SetStatusToCanceled extends GenericRequest
{
    const ACTION = 'SetStatusToCanceled';
    const ORDER_ITEM_ID = 'OrderItemId';
    const REASON = 'Reason';
    const REASON_DETAIL = 'ReasonDetail';

    /**
     * SetStatusToCanceled constructor.
     * @param int $orderItemId
     * @param string $reason
     * @param string $reasonDetail
     */
    public function __construct($orderItemId, $reason, $reasonDetail)
    {
        parent::__construct(
            Client::POST,
            static::ACTION,
            static::V1,
            [
                static::ORDER_ITEM_ID => $orderItemId,
                static::REASON => $reason,
                static::REASON_DETAIL => $reasonDetail
            ]
        );
    }
}
