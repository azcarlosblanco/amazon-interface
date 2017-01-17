<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\GenericRequest;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Response\ErrorResponse;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Response\GetBrands as GetBrandsResponse;

/**
 * Class GetBrands
 * @method GetBrandsResponse|ErrorResponse call(Client $client)
 */
class GetBrands extends GenericRequest
{
    const ACTION = 'GetBrands';

    /**
     * GetBrands constructor.
     */
    public function __construct()
    {
        parent::__construct(
            Client::GET,
            static::ACTION,
            static::V1
        );
    }

    /**
     * @return string
     */
    public function getResponseClassName()
    {
        return GetBrandsResponse::class;
    }
}
