<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\GenericRequest;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Response\ErrorResponse;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Response\FeedIdResponse;

/**
 * Class ProductCreate
 * @method FeedIdResponse|ErrorResponse call(Client $client)
 */
class ProductCreate extends GenericRequest
{
    const ACTION = 'ProductCreate';

    /**
     * ProductCreate constructor.
     *
     * @param array $productCreateData
     */
    public function __construct(array $productCreateData)
    {

        parent::__construct(
            Client::POST,
            static::ACTION,
            static::V1,
            [],
            $productCreateData
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
