<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Feed\Request;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\GenericRequest;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Response\ErrorResponse;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Feed\Response\FeedList as FeedListResponse;

/**
 * Class FeedList
 * @method FeedListResponse|ErrorResponse call(Client $client)
 */
class FeedList extends GenericRequest
{
    const ACTION = 'FeedList';

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
        return FeedListResponse::class;
    }
}
