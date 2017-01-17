<?php
namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\Signature;

interface RequestSignatureProviderInterface
{
    /**
     * @param string $requestAsString
     *
     * @return string
     */
    public function sign($requestAsString);
}
