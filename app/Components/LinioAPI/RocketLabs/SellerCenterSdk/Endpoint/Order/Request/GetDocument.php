<?php

namespace App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Order\Request;

use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Client;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Request\GenericRequest;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Core\Response\ErrorResponse;
use App\Components\LinioAPI\RocketLabs\SellerCenterSdk\Endpoint\Order\Response\GetDocument as GetDocumentResponse;

/**
 * Class GetDocument
 * @method GetDocumentResponse|ErrorResponse call(Client $client)
 */
class GetDocument extends GenericRequest
{
    const ACTION = 'GetDocument';
    const ORDER_ITEM_IDS = 'OrderItemIds';
    const DOCUMENT_TYPE = 'DocumentType';

    /**
     * GetDocument constructor.
     * @param int[] $orderItemIds
     * @param string $documentType
     */
    public function __construct(array $orderItemIds, $documentType)
    {
        parent::__construct(
            Client::GET,
            static::ACTION,
            static::V1,
            [
                static::ORDER_ITEM_IDS => json_encode(array_values($orderItemIds), true),
                static::DOCUMENT_TYPE => $documentType
            ]
        );
    }

    /**
     * @return string
     */
    public function getResponseClassName()
    {
        return GetDocumentResponse::class;
    }
}
