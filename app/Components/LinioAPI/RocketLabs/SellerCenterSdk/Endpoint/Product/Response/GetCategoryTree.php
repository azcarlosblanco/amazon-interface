<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Response;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Response\GenericResponse;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Model\Category;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Product\Model\CategoryCollection;

/**
 * Class GetCategoryTree
 */
class GetCategoryTree extends GenericResponse
{

    const CATEGORIES = 'Categories';
    const CATEGORY = 'Category';

    /**
     * @var CategoryCollection
     */
    private $categories;

    /**
     * @param array $responseData
     */
    public function processDecodedResponse(array $responseData)
    {
        parent::processDecodedResponse($responseData);

        $this->categories = new CategoryCollection();

        if (isset($this->getBody()[self::CATEGORIES][self::CATEGORY])) {
            foreach ($this->getBody()[self::CATEGORIES][self::CATEGORY] as $category) {
                $this->categories->add(new Category($category));
            }
        }
    }

    /**
     * @return CategoryCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

}
