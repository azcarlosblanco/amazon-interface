<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\GenericRequest;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Response\ErrorResponse;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Response\GetCategoryAttributes as GetCategoryAttributesResponse;

/**
 * Class GetCategoryAttributes
 * @method GetCategoryAttributesResponse|ErrorResponse call(Client $client)
 */
class GetCategoryAttributes extends GenericRequest
{
    const ACTION = 'GetCategoryAttributes';
    const PRIMARY_CATEGORY = 'PrimaryCategory';

    /**
     * GetCategoryAttributes constructor.
     *
     * @param int $primaryCategory
     */
    public function __construct($primaryCategory)
    {
        parent::__construct(
            Client::GET,
            static::ACTION,
            static::V1,
            [
                static::PRIMARY_CATEGORY => (int) $primaryCategory
            ]
        );
    }

    /**
     * @return string
     */
    public function getResponseClassName()
    {
        return GetCategoryAttributesResponse::class;
    }
}
