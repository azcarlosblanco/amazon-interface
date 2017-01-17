<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Model\Attribute;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Model\RestrictedArrayCollection;

/**
 * Class OptionCollection
 */
class OptionCollection extends RestrictedArrayCollection
{
    const ELEMENT_TYPE = Option::class;
}
