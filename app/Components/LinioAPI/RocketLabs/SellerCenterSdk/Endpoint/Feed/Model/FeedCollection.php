<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Feed\Model;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Model\RestrictedArrayCollection;

/**
 * Class FeedCollection
 */
class FeedCollection extends RestrictedArrayCollection
{
    const ELEMENT_TYPE = Feed::class;
}
