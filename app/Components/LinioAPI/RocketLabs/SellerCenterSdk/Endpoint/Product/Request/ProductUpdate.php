<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\GenericRequest;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Response\ErrorResponse;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Response\FeedIdResponse;

/**
 * Class ProductUpdate
 * @method FeedIdResponse|ErrorResponse call(Client $client)
 */
class ProductUpdate extends GenericRequest
{
    const ACTION = 'ProductUpdate';

    /**
     * ProductCreate constructor.
     *
     * @param array $productUpdateData
     */
    public function __construct(array $productUpdateData)
    {
        parent::__construct(
            Client::POST,
            static::ACTION,
            static::V1,
            [],
            $productUpdateData
        );
    }

    /**
     * @return string
     */
    public function getResponseClassName()
    {
        return FeedIdResponse::class;
    }
}
