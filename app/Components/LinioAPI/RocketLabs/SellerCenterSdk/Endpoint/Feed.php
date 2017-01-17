<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Feed\Request\FeedList;

/**
 * Class Feed
 */
class Feed
{
    /**
     * @return FeedList
     */
    public function feedList()
    {
        return new FeedList();
    }
}
