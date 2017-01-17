<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\Builder\Product;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\Builder\ProductCollectionAbstract;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\ProductCreate as ProductCreateRequest;

/**
 * Class ProductCreateCollection
 */
class ProductCreateCollection extends ProductCollectionAbstract
{
    /**
     * @return ProductCreateRequest
     */
    public function build()
    {
        return new ProductCreateRequest($this->toArray());
    }

    /**
     * @return ProductCreate
     */
    public function newProduct()
    {
        $productCreateBuilder = new ProductCreate();

        $this->products[] = $productCreateBuilder;

        return $productCreateBuilder;
    }
}
