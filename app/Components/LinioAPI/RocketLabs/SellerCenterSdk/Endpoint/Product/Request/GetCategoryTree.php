<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\GenericRequest;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Response\GetCategoryTree as GetCategoryTreeResponse;

/**
 * Class GetCategoryTree
 */
class GetCategoryTree extends GenericRequest
{

    const ACTION = 'GetCategoryTree';

    /**
     * GetCategoryTree constructor.
     */
    public function __construct()
    {
        parent::__construct(
            Client::GET,
            self::ACTION,
            self::V1
        );
    }

    /**
     * @return string
     */
    public function getResponseClassName()
    {
        return GetCategoryTreeResponse::class;
    }

}
