<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Model;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Model\RestrictedArrayCollection;

/**
 * Class BrandCollection
 */
class BrandCollection extends RestrictedArrayCollection
{
    const ELEMENT_TYPE = Brand::class;
}
