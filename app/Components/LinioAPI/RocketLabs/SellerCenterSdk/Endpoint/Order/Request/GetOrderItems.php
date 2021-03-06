<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Order\Request;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\GenericRequest;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Response\ErrorResponse;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Order\Response\GetOrderItems as GetOrderItemsResponse;

/**
 * Class GetOrderItems
 * @method GetOrderItemsResponse|ErrorResponse call(Client $client)
 */
class GetOrderItems extends GenericRequest
{
    const ACTION = 'GetOrderItems';
    const ORDER_ID_FIELD = 'OrderId';

    /**
     * GetOrderItems constructor.
     * @param int $orderId
     */
    public function __construct($orderId)
    {
        parent::__construct(Client::GET, static::ACTION, static::V1, [static::ORDER_ID_FIELD => $orderId]);
    }

    /**
     * @return string
     */
    public function getResponseClassName()
    {
        return GetOrderItemsResponse::class;
    }
}
