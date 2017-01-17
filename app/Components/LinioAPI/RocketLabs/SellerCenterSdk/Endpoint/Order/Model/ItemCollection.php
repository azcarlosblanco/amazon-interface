<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Order\Model;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Model\RestrictedArrayCollection;

/**
 * Class ItemCollection
 */
class ItemCollection extends RestrictedArrayCollection
{
    const ELEMENT_TYPE = Item::class;
}
