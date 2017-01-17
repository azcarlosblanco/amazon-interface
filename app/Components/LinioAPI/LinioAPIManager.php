<?php

namespace App\Components\LinioAPI;

use App\Components\LinioAPI\Contracts\LinioAPIManagerContract;
use DateTime;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Configuration;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Response\ErrorResponse;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Endpoints;

class LinioAPIManager implements LinioAPIManagerContract
{
    /**
     * Client Instance
     *
     * @var App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client
     */
    protected $client;


    public function __construct(array $credentials = Array())
    {
        $this->client = Client::create(new Configuration(
            $credentials['SC_API_URL'],
            $credentials['SC_API_USER'],
            $credentials['SC_API_KEY']
        ));
    }

    public function get()
    {
        $client = Client::create(new Configuration(
            $this->credentials['SC_API_URL'],
            $this->credentials['SC_API_USER'],
            $this->credentials['SC_API_KEY']
        ));

        $productCollectionRequest = Endpoints::product()->productCreate();

        $brand = 'ABC'; // Please change the brand
        $primaryCategory = 11067; // Please change the primary category

        $sellerSku = 'r5g92hk'; // Please change SellerSku to your convenience

        $productCollectionRequest->newProduct()
            ->setName('Jumpler ABC Grey')
            ->setSellerSku($sellerSku)
            ->setStatus('active')
            ->setVariation('XXL')
            ->setPrimaryCategory($primaryCategory)
            ->setDescription('lorem ...')
            ->setBrand($brand)
            ->setDeliveryTimeSupplier(2)
            ->setProductId('4549064')
            ->setQuantity(1)
            ->setShipmentType('dropshipping')
            ->setPrice(400000)
            ->setSalePrice(390000)
            ->setSaleStartDate(new DateTime('now'))
            ->setSaleEndDate((new DateTime('now'))->modify('+5 day'))
            ->setTaxClass(16);

        $response = $productCollectionRequest->build()->call($client);

        dd($response);

        if ($response instanceof ErrorResponse) {
            /** @var ErrorResponse $response */
            printf("ERROR !\n");
            printf("%s\n", $response->getMessage());
        } else {
            printf("The feed `%s` has been created.\n", $response->getFeedId());
        }


    }

    /**
     * Get all brands in linio
     *
     * @param type var Description
     * @return {11:return type}
     */
    public function getBrands()
    {
        $response = Endpoints::product()->getBrands()->call($this->client);

        if ($response instanceof ErrorResponse) {
            return array(
                'error' => true,
                'message' => $response->getMessage(),
            );
        } else {
            return $response->getBrands();
        }
    }
}
