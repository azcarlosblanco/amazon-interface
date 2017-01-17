<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\Builder\Product;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\Builder\ProductCollectionAbstract;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\ProductUpdate as ProductUpdateRequest;

/**
 * Class ProductUpdateCollection
 */
class ProductUpdateCollection extends ProductCollectionAbstract
{
    /**
     * @return ProductUpdateRequest
     */
    public function build()
    {
        return new ProductUpdateRequest($this->toArray());
    }

    /**
     * @param string $sellerSku
     *
     * @return ProductUpdate
     */
    public function updateProduct($sellerSku)
    {
        $productUpdateBuilder = new ProductUpdate($sellerSku);

        $this->products[] = $productUpdateBuilder;

        return $productUpdateBuilder;
    }
}
