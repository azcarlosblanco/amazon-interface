<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request;

/**
 * Interface RequestBuilderInterface
 */
interface RequestBuilderInterface
{

    /**
     * @return RequestInterface
     */
    public function build();

}
