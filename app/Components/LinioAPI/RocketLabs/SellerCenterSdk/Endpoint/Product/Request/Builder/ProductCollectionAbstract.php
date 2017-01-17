<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\Builder;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\Builder\RequestBuilderAbstract;

/**
 * Class ProductCollectionAbstract
 */
abstract class ProductCollectionAbstract extends RequestBuilderAbstract
{

    const AGGREGATOR_NAME = 'Product';

    /**
     * @var ProductAbstract
     */
    protected $products;

    /**
     * @return array
     */
    public function toArray()
    {
        $products = [];

        /** @var ProductAbstract $product */
        foreach ($this->products as $product) {
            $products[static::AGGREGATOR_NAME][] = $product->toArray();
        }

        return $products;
    }
}
