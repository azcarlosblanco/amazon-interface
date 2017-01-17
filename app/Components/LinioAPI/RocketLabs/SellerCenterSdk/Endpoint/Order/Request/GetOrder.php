<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Order\Request;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\GenericRequest;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Response\ErrorResponse;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Order\Response\GetOrder as GetOrderResponse;

/**
 * Class GetOrder
 * @method GetOrderResponse|ErrorResponse call(Client $client)
 */
class GetOrder extends GenericRequest
{
    const ACTION = 'GetOrder';
    const ORDER_ID_FIELD = 'OrderId';

    /**
     * @param string $orderId
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
        return GetOrderResponse::class;
    }
}
