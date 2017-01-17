<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Order\Model;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Model\RestrictedArrayCollection;

/**
 * Class OrderCollection
 */
class OrderCollection extends RestrictedArrayCollection
{
    const ELEMENT_TYPE = Order::class;
}
