<?php
namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\OutputFormatAdapter;

/**
 * Interface OutputFormatAdapterInterface
 */
interface OutputFormatAdapterInterface
{
    /**
     * @param array $bodyContent
     *
     * @return string|false
     */
    public function convertToOutputFormat(array $bodyContent);
}
