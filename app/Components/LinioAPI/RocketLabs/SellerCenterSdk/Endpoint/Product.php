<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\Builder\GetProducts;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\Builder\Product\ProductCreateCollection;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\Builder\Product\ProductUpdateCollection;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\GetCategoryAttributes as GetCategoryAttributesRequest;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\Builder\Image;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\GetBrands;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Request\GetCategoryTree;

/**
 * Class Product
 */
final class Product
{
    /**
     * @return GetBrands
     */
    public function getBrands()
    {
        return new GetBrands();
    }

    /**
     * @return ProductCreateCollection
     */
    public function productCreate()
    {
        return new ProductCreateCollection();
    }

    /**
     * @return ProductUpdateCollection
     */
    public function productUpdate()
    {
        return new ProductUpdateCollection();
    }

    /**
     * @return GetProducts
     */
    public function getProducts()
    {
        return new GetProducts();
    }

    /**
     * @param int $primaryCategory
     *
     * @return GetCategoryAttributesRequest
     */
    public function getCategoryAttributes($primaryCategory)
    {
        return new GetCategoryAttributesRequest($primaryCategory);
    }

    /**
     * @param string $sellerSku
     * @return Image
     */
    public function image($sellerSku)
    {
        return new Image($sellerSku);
    }

    /**
     * @return GetCategoryTree
     */
    public function getCategoryTree()
    {
        return new GetCategoryTree();
    }

}
