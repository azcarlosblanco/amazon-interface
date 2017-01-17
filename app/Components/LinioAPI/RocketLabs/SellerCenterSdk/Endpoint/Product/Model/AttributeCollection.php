<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Model;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Model\RestrictedArrayCollection;

/**
 * Class AttributeCollection
 */
class AttributeCollection extends RestrictedArrayCollection
{
    const ELEMENT_TYPE = Attribute::class;
}
