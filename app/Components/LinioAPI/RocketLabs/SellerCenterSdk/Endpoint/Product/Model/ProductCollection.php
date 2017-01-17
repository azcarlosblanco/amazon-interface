<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Model;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Model\RestrictedArrayCollection;

/**
 * Class ProductCollection
 */
class ProductCollection extends RestrictedArrayCollection
{
    const ELEMENT_TYPE = Product::class;
}
