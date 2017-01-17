<?php

 namespace app\Components\AmazonAPI;

 use App\Components\AmazonAPI\Contracts\AmazonAPIManagerContract;
 use SimpleXMLElement;
 use Exception;
 use ErrorException;

class AmazonAPIManager implements AmazonAPIManagerContract
{
    protected $endpoint = 'webservices.amazon.com';

    protected $uri = '/onca/xml';

    protected $credentials;

    protected $parameters = array();

    public function __construct(array $credentials = Array())
    {
        $this->credentials = $credentials;
    }

    public function itemSearch(array $parameters = Array())
    {
        $this->parameters = $parameters;

        $this->checkAmazonAPIConflicts($parameters);

        $results = $this->resolveMultipleQuerys($this->parameters, config('amazonAPI.credentials.results_per_page'));

        return $results;
    }

    public function browseNodeLookup($node)
    {
        $endpoint = $this->endpoint;

        $uri = $this->uri;

        $params = array(
            "Service" => "AWSECommerceService",
            "Operation" => "BrowseNodeLookup",
            "AWSAccessKeyId" => $this->credentials['access_key_id'],
            "AssociateTag" => $this->credentials['tracking_id'],
            "BrowseNodeId" => $node,
            "ResponseGroup" => "BrowseNodeInfo"
        );

        if (!isset($params["Timestamp"])) {
            $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
        }

        ksort($params);

        $pairs = array();
        foreach ($params as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }

        $canonical_query_string = join("&", $pairs);

        $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $this->credentials['secret_access_key'], true));

        $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

        $response_xml_data = $this->fileGetContents($request_url);

        return $this->toASimpleXMLElementObject($response_xml_data);

    }

    protected function queryResult(array $parameters = Array())
    {
        $endpoint = $this->endpoint;

        $uri = $this->uri;

        $params = array(
            "Service" => "AWSECommerceService",
            "Operation" => "ItemSearch",
            "AWSAccessKeyId" => $this->credentials['access_key_id'],
            "AssociateTag" => $this->credentials['tracking_id'],
            "SearchIndex" => $parameters['category'],
            "Keywords" => $parameters['keywords'],
            "ResponseGroup" => "Images,ItemAttributes,ItemIds,Offers,BrowseNodes",
            "Sort" => $parameters['sort'],
            "Availability" => "Available",
            "Condition" => "New",
            "ItemPage" => $parameters['page'],
            "BrowseNode" => isset($parameters['node']) ? $parameters['node'] : '',
        );

        if (!isset($params["Timestamp"])) {
            $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
        }

        ksort($params);

        $pairs = array();

        foreach ($params as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }

        $canonical_query_string = join("&", $pairs);

        $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $this->credentials['secret_access_key'], true));

        $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

        $response_xml_data = $this->fileGetContents($request_url);

        return $this->toASimpleXMLElementObject($response_xml_data);
    }

    /**
     * toASimpleXMLElementObject description
     * @param String $response_xml_data
     */
    protected function toASimpleXMLElementObject($response_xml_data)
    {
        try {

            return new SimpleXMLElement($response_xml_data);

        } catch (Exception $e) {

            $errors = new SimpleXMLElement("<errors></errors>");
            $errors->addChild('error', 'Ocurrio un error en la conexión con Amazon. SimpleXMLElementObject');
            return $errors;

        }
    }


    /**
     * fileGetContents description
     * @param String $request_url
     */
    protected function fileGetContents($request_url)
    {
        try {

            return file_get_contents($request_url);

        } catch (ErrorException $e) {

            $errors = new SimpleXMLElement("<errors></errors>");
            $errors->addChild('error', 'Ocurrio un error en la conexión con Amazon. ErrorException');
            return $errors;


        } catch (Exception $e) {

            $errors = new SimpleXMLElement("<errors></errors>");
            $errors->addChild('error', 'Ocurrio un error en la conexión con Amazon. Exception');
            return $errors;

        }
    }


    protected function checkAmazonAPIConflicts($parameters)
    {
        if (isset($parameters['category'])) {
            if ($parameters['category'] == 'All' && !empty($parameters['sort'])) {
                \Session::flash('alert', 'Cuando se obtienen los items de todas las categorias no se pueden ordenar los resultados. Hemos eliminado el parametro <<Ordenar>> de la busqueda');
                \Request::merge(['sort' => '']);
                $this->parameters['sort'] = '';
            }
            if (empty($parameters['category'])) {
                $this->parameters['category'] = 'All';
            }
        }


        if (isset($parameters['node']) && !is_null($parameters['node']) && (!isset($parameters['child']) || is_null($parameters['child']))) {
            $this->parameters['category'] = array_search($parameters['node'], config('amazonAPI.category_browse_node'));
            $this->parameters['keywords'] = '';
            $this->parameters['sort'] = '';

        }

        if ((isset($parameters['node']) && !is_null($parameters['node'])) && (isset($parameters['child']) && !is_null($parameters['child']))) {
            $this->parameters['category'] = array_search($parameters['node'], config('amazonAPI.category_browse_node'));
            $this->parameters['keywords'] = '';
            $this->parameters['sort'] = '';
            $this->parameters['node'] = $parameters['child'];
        }

    }

    protected function resolveMultipleQuerys(array $parameters, $numOfResults)
    {
        if ($numOfResults % 10) {
            $errors = new SimpleXMLElement("<errors></errors>");
            $errors->addChild('error', 'La cantidad de resultados a mostrar por pagina debe ser multiplo de 10');
            return $errors;

        }

        $results = array();
        $pageF = (isset($parameters['page']) ? $parameters['page'] : 1) * ($numOfResults/10);
        $pageI = $pageF - (($numOfResults/10) - 1);

        for ($pageI; $pageI <= $pageF; $pageI++) {
            $parameters['page'] = $pageI;
            $results[] = $this->queryResult($parameters);
        }

        return $results;
    }

    public function itemLookup($asin)
    {

        $endpoint = $this->endpoint;

        $uri = $this->uri;

        $params = array(
            "Service" => "AWSECommerceService",
            "Operation" => "ItemLookup",
            "AWSAccessKeyId" => $this->credentials['access_key_id'],
            "AssociateTag" => $this->credentials['tracking_id'],
            "ItemId" => $asin,
            "IdType" => "ASIN",
            "ResponseGroup" => "BrowseNodes,Images,ItemAttributes,Offers,OfferSummary"        );

        if (!isset($params["Timestamp"])) {
            $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
        }

        ksort($params);

        $pairs = array();

        foreach ($params as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }

        $canonical_query_string = join("&", $pairs);

        $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $this->credentials['secret_access_key'], true));

        $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

        $response_xml_data = $this->fileGetContents($request_url);

        return $this->toASimpleXMLElementObject($response_xml_data);
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     * @return {11:return type}
     */
    public function cartCreate(array $items)
    {
        // The region you are interested in
        $endpoint = $this->endpoint;

        $uri = $this->uri;

        $configuration = array(
            "Service" => "AWSECommerceService",
            "Operation" => "CartCreate",
            "AWSAccessKeyId" => $this->credentials['access_key_id'],
            "AssociateTag" => $this->credentials['tracking_id'],
            "ResponseGroup" => "Cart",
        );

        $params = array_merge($configuration, $items);

        if (!isset($params["Timestamp"])) {
            $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
        }

        ksort($params);

        $pairs = array();

        foreach ($params as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }

        $canonical_query_string = join("&", $pairs);

        $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $this->credentials['secret_access_key'], true));

        $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

        $response_xml_data = $this->fileGetContents($request_url);

        return $this->toASimpleXMLElementObject($response_xml_data);
    }

}
