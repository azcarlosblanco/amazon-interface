<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Model;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Model\RestrictedArrayCollection;

/**
 * Class CategoryTreeCollection
 */
class CategoryCollection extends RestrictedArrayCollection
{
    const ELEMENT_TYPE = Category::class;
}
