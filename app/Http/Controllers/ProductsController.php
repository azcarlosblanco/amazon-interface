<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Components\AmazonAPI\Contracts\AmazonAPIManagerContract;
use App\Components\LinioAPI\Contracts\LinioAPIManagerContract;
use App\Entities\Alert;
use App\Entities\Cart;
use App\Entities\Order;
use App\Entities\Product;
use App\Entities\Parameter;
use App\Entities\Credential;
use App\Entities\AmazonSearch;
use App\Entities\ProductDetail;
use App\Components\MercadolibreAPI\Meli\meli as Meli;
use App\Components\TranslateAPI\GoogleTranslate;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::with('detail')
            ->findByPublish($request->published)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('home.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkMeliOrders(Request $request, AmazonAPIManagerContract $amazonAPIManager)
    {
        $orders = Order::notProcessed()->get();

        if (count($orders) == 0) {
            return redirect()
                ->back()
                ->with('alert', 'No existen ordenes en Mercadolibre que procesar');
        }

        $meli = new Meli(
            config('mercadolibreAPI.credentials.app_id'),
            config('mercadolibreAPI.credentials.secret_key')
        );

        $params = [
            'access_token' => Credential::find(1)->access_token
        ];

        $items = array();
        $numItems = 1;

        foreach ($orders as $order) {

            $response = $meli->get($order->resource, $params);
            if ($response['httpCode'] != 200) {
                return redirect()
                    ->back()
                    ->with('alert', 'Mercadolibre devolvio un error en la busqueda');
            }

            $order->update([
                'order_id'      => $response['body']->id,
                 'status'       => $response['body']->status,
                 'product_id'   => $response['body']->order_items[0]->item->id,
                 'nickname'     => isset($response['body']->buyer->nickname) ? $response['body']->buyer->nickname : '',
                 'name'         => (isset($response['body']->buyer->first_name) && isset($response['body']->buyer->last_name)) ? $response['body']->buyer->first_name . ' ' . $response['body']->buyer->last_name : '',
                 'email'        => isset($response['body']->buyer->email) ? $response['body']->buyer->email : '',
                 'phone'        => isset($response['body']->buyer->phone->area_code) ? $response['body']->buyer->phone->area_code . $response['body']->buyer->phone->number : '',
                 'total_amount' => $response['body']->total_amount,
                 'paid_amount'  => $response['body']->paid_amount,
                 'processed'    => true,
            ]);

            if ($response['body']->status == 'paid' && $response['body']->total_amount == $response['body']->paid_amount) {

                $product = Product::isMeliId($response['body']->order_items[0]->item->id)->first();

                if(is_null($product)) {
                    Alert::create( [
                        'description' => 'Se ha eliminado la orden ' . $order->id . ' hecha por el usuario ' . $order->nickname . ' debido a que no se encontro en la aplicacion.',
                        'order' => null,
                        ]);

                    $alerts = Alert::where('order', $order->resource)->get();

                    foreach ($alerts as $alert) {
                        $alert->delete();
                    }

                    $order->delete();
                    continue;
                }

                $item = $amazonAPIManager->itemLookup($product->amazon_id);

                if (isset($item->Items->Request->Errors)) {
                    Alert::create( [
                        'description' => 'La busqueda fue erronea. Amazon devolvió el siguiente error en al busqueda: ' . $item->Items->Request->Errors->Error->Message,
                        'order' => $order->resource,
                        ]);

                    continue;
                }

                if ((Int)$item->Items->Item->Offers->TotalOffers === 0) {
                    Alert::create( [
                        'description' => 'Un producto devolvio error al procesar orden',
                        'order' => $order->resource,
                        ]);

                    $order->update([
                         'rejected'  => true,
                    ]);

                    continue;
                }

                $price = (int) $item->Items->Item->Offers->Offer->OfferListing->Price->Amount->__toString() / 100;

                if ($price > (float)$product->detail->price) {
                    Alert::create( [
                        'description' => 'Ha habido una devolucion de producto por diferencia de precios',
                        'order' => $order->resource,
                        ]);

                    $order->update([
                        'rejected'  => true,
                    ]);

                    continue;
                }

                $product->update([
                    'ml_p' => false,
                    'amazon_c' => true,
                ]);


                $items['Item.' . $numItems . '.OfferListingId'] = $product->detail()->first()->offer;
                $items['Item.' . $numItems . '.Quantity'] = $response['body']->order_items[0]->quantity;


                $numItems++;
                if ($numItems >= 50) break;

            }
        }

        if (empty($items)) {
            return redirect()
                ->back()
                ->with('alert', 'Las ordenes que se intentaron procesar no se encontraban en el sistema.');
        }

        $result = $amazonAPIManager->cartCreate($items);

        if ($result->Cart->Request->IsValid->__toString() == 'False') {
            foreach ($orders as $order) {
                $order->update([
                     'processed'    => false,
                ]);
            }
            return redirect()
                ->back()
                ->with('alert', 'Amazon devolvió el siguiente error en el la petición: ' . $result->Cart->Request->Errors->Error->Message->__toString());
        }

        $cart = new Cart([
            'cart_id' => $result->Cart->CartId->__toString(),
            'hmac' => $result->Cart->HMAC->__toString(),
            'purchase_url' => $result->Cart->PurchaseURL->__toString(),
            'num_items' => count($result->Cart->CartItems->CartItem),
            'amount' => (int) $result->Cart->SubTotal->Amount->__toString(),
        ]);

        if ($cart->saveOrFail()) {
            foreach ($orders as $order) {
                $order->update([
                     'cart_id' => $cart->cart_id,
                ]);
            }
        }

        return redirect()
        ->back()
        ->with('success', 'Se ha creado el carrito de compras para Amazon ' . $cart->cart_id . ' con la cantidad de ' . $cart->num_items . ' articulos');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateMeliOrders(Request $request, AmazonAPIManagerContract $amazonAPIManager)
    {
        dd($request);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function returnMeliOrders(Request $request, AmazonAPIManagerContract $amazonAPIManager)
    {

        $orders = Order::Rejected()->get();

        if (count($orders) == 0) {
            return redirect()
                ->back()
                ->with('alert', 'No existen devoluciones que procesar');
        }

        $meli = new Meli(
            config('mercadolibreAPI.credentials.app_id'),
            config('mercadolibreAPI.credentials.secret_key')
        );

        $params = [
            'access_token' => Credential::find(1)->access_token
        ];

        $body = [
            "fulfilled" => false,
            "rating" => "neutral",
            "message" => "La operacion no se completo",
            "reason" => "SELLER_OUT_OF_STOCK",
            "restock_item" => false,
            "has_seller_refunded_money" => false
        ];


        foreach ($orders as $order) {

            $response = $meli->post($order->resource, $body, $params);

            if ($response['httpCode'] != 200) {
                return redirect()
                    ->back()
                    ->with('alert', 'Mercadolibre devolvio un error en la busqueda: ' . $response['body']->message);
            }

            $order->delete();

        }

        return redirect()
        ->back()
        ->with('success', 'Las ordenes fueron calificadas como no completadas, asegurece de que se haya hecho una devolucion del dinero al comprador.');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function meliNotification(Request $request)
    {
        $response = json_decode($request->getContent(), true);

        $flight = Order::updateOrCreate(
            ['resource' => $response['resource']],
            [
                'resource' => $response['resource'],
                'user_id' => $response['user_id'],
            ]
        );

        if ($flight->wasRecentlyCreated) {
            Alert::create( [
                'description' => 'Se ha recibido una orden: ' . $response['resource'] . ' de el usuario de mercadolibre ' . $response['user_id'],
                'order' => $response['resource'],
            ]);
        }

        return http_response_code(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMeli(Request $request, AmazonAPIManagerContract $amazonAPIManager)
    {
        $result = $amazonAPIManager->itemLookup($request->amazonId);

        if (isset($result->Items->Request->Errors)) {
            return redirect()
                ->back()
                ->with('alert', 'La busqueda fue erronea. Amazon devolvió el siguiente error en al busqueda: ' . $result->Items->Request->Errors->Error->Message);
        }

        $asin = $result->Items->Item->ASIN->__toString();

        if (count(Product::isAsin($asin)->get())) {
            return redirect()
                ->back()
                ->with('alert', 'Este articulo ya fue publicado en Mercadolibre');
        }

        $feature = '';

        foreach ($result->Items->Item->ItemAttributes->Feature as $key => $value) {
            $feature .= $value . '. ';
        }

        if (isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight)) {
            $weight_lbs = (int) $result->Items->Item->ItemAttributes->PackageDimensions->Weight->__toString();
            $weight_lbs = $weight_lbs / 100; //move 2 decimal to the left
            $weight_kls = $weight_lbs / 2.20462;
        }

        if ((Int)$result->Items->Item->Offers->TotalOffers === 0) {
            return redirect()
                ->back()
                ->with('alert', 'La busqueda fue erronea. El articulo que selecciono no tiene ofertas');
        }

        $item = [
            'amazon_id' => $result->Items->Item->ASIN->__toString(),
            'ml_id'     => '',
            // 'li_id'     => null,
            // db default(false)
            // 'amazon_c'  => '',
            'ml_p'      => true,
            // 'li_p'     => '',
            // 'changed'   => '',
        ];

        if ($result->Items->Item->Offers->Offer->OfferListing->Price->FormattedPrice->__toString() == 'Too low to display') {
            return redirect()
                ->back()
                ->with('alert', 'El articulo que selecciono no tiene ofertas, tiene incongruencias con el precio');
        }

        $parameters = Parameter::findOrfail(1)->first()->toArray();

        $price = (int) $result->Items->Item->Offers->Offer->OfferListing->Price->Amount->__toString() / 100;

        $ship = $parameters['costo_envio_kg'] * (isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight) ? $weight_kls : $parameters['default_weight']);
        $price_usa = $price + ($price * $parameters['tax_usa']);
        $price_COP = $price_usa * $parameters['TRM'];
        $costo_co = $ship + $price_COP;
        $costo_ut = $costo_co + ($costo_co * $parameters['utilidad']);
        $costo_iva = $costo_ut + ($costo_ut * $parameters['iva_co']);
        $costo_ml = $costo_iva + ($costo_ut * $parameters['comision_meli']);
        $costo_final_ml = (ceil(round($costo_ml, 0, PHP_ROUND_HALF_UP) / 100) * 100);

        $details = [
            'title'          => $result->Items->Item->ItemAttributes->Title->__toString(),
            'img_url'        => isset($result->Items->Item->LargeImage->URL) ? $result->Items->Item->LargeImage->URL->__toString() : null,
            'img_set'        => isset($result->Items->Item->ImageSets->ImageSet[0]) ? $result->Items->Item->ImageSets->ImageSet[0]->LargeImage->URL->__toString() : null,
            'brand'          => $result->Items->Item->ItemAttributes->Brand->__toString(),
            'departament'    => $result->Items->Item->ItemAttributes->Department->__toString(),
            'feature'        => $feature,
            'offer'          => $result->Items->Item->Offers->Offer->OfferListing->OfferListingId->__toString(),
            'weight'         => isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight) ? $weight_kls : $parameters['default_weight'],
            'price'          => $price,
            'costo_co'       => $costo_co,
            'costo_ut'       => $costo_ut,
            'costo_final_ml' => $costo_final_ml,
            // 'costo_final_li' => '',

        ];

        $imageSets = [];

        $imageMain = [];
        $imageMain['source'] = isset($result->Items->Item->LargeImage->URL) ? $result->Items->Item->LargeImage->URL->__toString() : null;

        $imageSets[] = $imageMain;

        foreach ($result->Items->Item->ImageSets->ImageSet as $image) {
            $array = [];
            $array['source'] = $image->LargeImage->URL->__toString();
            $imageSets[] = $array;
        }

        $details['title'] = GoogleTranslate::translate($details['title']);
        $details['feature'] = GoogleTranslate::translate($details['feature']);

        if (isset($result->Items->Item->ItemAttributes->Warranty)) {
            $warranty = GoogleTranslate::translate($result->Items->Item->ItemAttributes->Warranty->__toString());
        }

        $meli = new Meli(
            config('mercadolibreAPI.credentials.app_id'),
            config('mercadolibreAPI.credentials.secret_key')
        );

        $ancestor = $result->Items->Item->BrowseNodes;
        while (isset($ancestor)) {
            if (isset($ancestor->BrowseNode->Ancestors)) {
                $ancestor = $ancestor->BrowseNode->Ancestors;
            } else {
                break;
            }
        }

        $content = [
            'title' => str_limit(str_replace(' ', '%', $details['title']), 200),
            // 'category_from' => config('amazonAPI.equivalences.' . $ancestor->BrowseNode->BrowseNodeId->__toString() . '.meli_equivalence'),
            'price' => $details['costo_final_ml'],
            'seller_id' => config('mercadolibreAPI.credentials.user_id')
        ];

        $predictor = $meli->get('/sites/MCO/category_predictor/predict', $content);
        $atributtes = $meli->get('/categories/' . $predictor['body']->id . '/attributes');

        $atributtes_names = array();
        foreach ($atributtes['body'] as $value) {
            if (isset($value->tags->required) && $value->tags->required = true) {
                $atributtes_names[] = $value->name;
            }
        }

        if ($predictor['httpCode'] != 200) {
            return redirect()
                ->back()
                ->with('alert', 'Hubo un problema traspasando las categorias.');
        }

        $body = [
            'title' => str_limit($details['title'], 57),
            'category_id' => $predictor['body']->id,
            'price' => $details['costo_final_ml'],
            'currency_id' => 'COP',
            'available_quantity' => 1,
            'buying_mode' => 'buy_it_now',
            'listing_type_id' => 'gold_special',
            'condition' => 'new',
            'description' => "<html xmlns='http://www.w3.org/1999/xhtml'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><title>Documento sin t�tulo</title> <style type='text/css'> .center { text-align: justify; font-size: 16px; font-weight: bold; color: #006887; padding:10px 20px} </style> </head> <body> <table width='793' border='0' align='center' cellpadding='0' cellspacing='0'> <tbody><tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/akaes-logo.png' width='793' height='101'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/productos-importados.png' width='793' height='405'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/n-productos.png' width='793' height='131'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/descripcion.png' width='793' height='72'></td> </tr> <tr> <td background='http://acaescolombia.com/PLANTILLAFINAL/fondo-azul.png'> <p class='center'>{$details['feature']}</p> <p class='center'>&nbsp;</p> </td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/barra-color.png' width='793' height='20'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/como-comprar.png' width='793' height='511'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/donde-pagar.png' width='793' height='413'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes1.png' width='793' height='314'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes2.png' width='793' height='169'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes3.png' width='793' height='164'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes4.png' width='793' height='200'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/akaes-footer.png' width='793' height='213'></td> </tr> </tbody></table> </body></html>",
            'video_id' =>  null,
            'tags' => [
                        'immediate_payment'
                    ],
            'warranty' =>  isset($result->Items->Item->ItemAttributes->Warranty) ? $warranty : 'Con el fabricante',
            'pictures' => $imageSets
        ];

        $variations = [
               [
                   'attribute_combinations' => [],
                    'available_quantity' => 1,
                    'price' => $body['price'],
                    'picture_ids' => [
                             $imageMain['source']
                         ]
               ]
            ];

        foreach ($atributtes_names as $value) {
            if ($value = 'Color Primario') {
                $id = '11000';
                $color = '2105d8e';
            }
            $variations[0]['attribute_combinations'][] = [
                'id' => '11000',
                'value_id' => '02bb186'
            ];
        }

        if (!empty($atributtes_names)) {
            $body['variations'] = $variations;
        }

        $params = [
            'access_token' => Credential::find(1)->access_token,
        ];

        $response = $meli->post('/items', $body, $params);

        if ($response['httpCode'] === 400) {
            return redirect()
                ->back()
                ->with('alert', 'Mercadolibre devolvio un error en la publicación, favor revisar si tiene acceso para publicar en su cuenta de Mercadolibre.');
        }

        $item['ml_id'] = $response['body']->id;
        $product = new Product($item);
        $productDetails = new ProductDetail($details);

        $product->saveOrFail();
        $product->detail()->save($productDetails);

        $link = \Html::decode("<a href='{$response['body']->permalink}' target='_blank'>{$response['body']->permalink}</a>");

        return redirect()
            ->back()
            ->with('success', 'La publicacion en Mercadolibre fue realizada con exito.')
            ->with('link', $link);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMeliMasive(Request $request, AmazonAPIManagerContract $amazonAPIManager)
    {

        if ($request->ajax() || $request->isMethod('get')) {

            $notifications = array();

            if (is_null($request->_list)) {
                $notifications[] = [
                    'content' => 'Debe enviar al menos un producto, seleccione uno por favor.',
                    'success' => false,
                ];
                return $notifications;
            }

            foreach ($request->_list as $amazonId) {
                $result = $amazonAPIManager->itemLookup($amazonId);

                if (isset($result->Items->Request->Errors)) {
                    $notifications[] = [
                        'content' => $result->Items->Request->Errors->Error->Message,
                        'success' => false,
                    ];
                    continue;
                }

                $asin = $result->Items->Item->ASIN->__toString();
                if (count(Product::isAsin($asin)->get())) {
                    $notifications[] = [
                        'content' => 'El articulo '. $asin .' ya fue publicado en Mercadolibre',
                        'success' => false,
                    ];
                    continue;
                }

                $feature = '';

                foreach ($result->Items->Item->ItemAttributes->Feature as $key => $value) {
                    $feature .= $value . '. ';
                }

                if (isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight)) {
                    $weight_lbs = (int) $result->Items->Item->ItemAttributes->PackageDimensions->Weight->__toString();
                    $weight_lbs = $weight_lbs / 100; //move 2 decimal to the left
                    $weight_kls = $weight_lbs / 2.20462;
                } else {
                    $notifications[] = [
                        'content' => 'El peso en el articulo ' . $asin . ' no esta definido',
                        'success' => false,
                    ];
                }

                if ((Int)$result->Items->Item->Offers->TotalOffers === 0) {
                    $notifications[] = [
                        'content' => 'El articulo ' . $asin . ' que selecciono no tiene ofertas',
                        'success' => false,
                    ];
                    continue;
                }

                $item = [
                    'amazon_id' => $result->Items->Item->ASIN->__toString(),
                    'ml_id'     => '',
                    'li_id'     => null,
                    // db default(false)
                    // 'amazon_c'  => '',
                    'ml_p'      => true,
                    // 'li_p'     => '',
                    // 'changed'   => '',
                ];

                if ($result->Items->Item->Offers->Offer->OfferListing->Price->FormattedPrice->__toString() == 'Too low to display') {
                    $notifications[] = [
                        'content' => 'El articulo ' . $asin . ' que selecciono no tiene ofertas, tiene incongruencias con el precio',
                        'success' => false,
                    ];
                    continue;
                }

                $parameters = Parameter::findOrfail(1)->first()->toArray();

                $price = (int) $result->Items->Item->Offers->Offer->OfferListing->Price->Amount->__toString() / 100;

                $ship = $parameters['costo_envio_kg'] * (isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight) ? $weight_kls : $parameters['default_weight']);
                $price_usa = $price + ($price * $parameters['tax_usa']);
                $price_COP = $price_usa * $parameters['TRM'];
                $costo_co = $ship + $price_COP;
                $costo_ut = $costo_co + ($costo_co * $parameters['utilidad']);
                $costo_iva = $costo_ut + ($costo_ut * $parameters['iva_co']);
                $costo_ml = $costo_iva + ($costo_ut * $parameters['comision_meli']);
                $costo_final_ml = (ceil(round($costo_ml, 0, PHP_ROUND_HALF_UP) / 100) * 100);

                $details = [
                    'title'          => $result->Items->Item->ItemAttributes->Title->__toString(),
                    'img_url'        => isset($result->Items->Item->LargeImage->URL) ? $result->Items->Item->LargeImage->URL->__toString() : null,
                    'img_set'        => isset($result->Items->Item->ImageSets->ImageSet[0]) ? $result->Items->Item->ImageSets->ImageSet[0]->LargeImage->URL->__toString() : null,
                    'brand'          => $result->Items->Item->ItemAttributes->Brand->__toString(),
                    'departament'    => $result->Items->Item->ItemAttributes->Department->__toString(),
                    'feature'        => $feature,
                    'offer'          => $result->Items->Item->Offers->Offer->OfferListing->OfferListingId->__toString(),
                    'weight'         => isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight) ? $weight_kls : $parameters['default_weight'],
                    'price'          => $price,
                    'costo_co'       => $costo_co,
                    'costo_ut'       => $costo_ut,
                    'costo_final_ml' => $costo_final_ml,
                    // 'costo_final_li' => '',

                ];

                $imageSets = [];

                $imageMain = [];
                $imageMain['source'] = isset($result->Items->Item->LargeImage->URL) ? $result->Items->Item->LargeImage->URL->__toString() : null;

                $imageSets[] = $imageMain;

                foreach ($result->Items->Item->ImageSets->ImageSet as $image) {
                    $array = [];
                    $array['source'] = $image->LargeImage->URL->__toString();
                    $imageSets[] = $array;
                }

                $details['title'] = GoogleTranslate::translate($details['title']);
                $details['feature'] = GoogleTranslate::translate($details['feature']);

                if (isset($result->Items->Item->ItemAttributes->Warranty)) {
                    $warranty = GoogleTranslate::translate($result->Items->Item->ItemAttributes->Warranty->__toString());
                }

                $meli = new Meli(
                    config('mercadolibreAPI.credentials.app_id'),
                    config('mercadolibreAPI.credentials.secret_key')
                );


                $ancestor = $result->Items->Item->BrowseNodes;
                while (isset($ancestor)) {
                    if (isset($ancestor->BrowseNode->Ancestors)) {
                        $ancestor = $ancestor->BrowseNode->Ancestors;
                    } else {
                        break;
                    }
                }

                $content = [
                    'title' => str_limit(str_replace(' ', '%', $details['title']), 200),
                    // 'category_from' => config('amazonAPI.equivalences.' . $ancestor->BrowseNode->BrowseNodeId->__toString() . '.meli_equivalence'),
                    'price' => $details['costo_final_ml'],
                    'seller_id' => config('mercadolibreAPI.credentials.user_id')
                ];

                $predictor = $meli->get('/sites/MCO/category_predictor/predict', $content);
                $atributtes = $meli->get('/categories/' . $predictor['body']->id . '/attributes');

                $atributtes_names = array();
                foreach ($atributtes['body'] as $value) {
                    if (isset($value->tags->required) && $value->tags->required = true) {
                        $atributtes_names[] = $value->name;
                    }
                }

                if ($predictor['httpCode'] != 200) {
                    $notifications[] = [
                        'content' => 'Hubo un problema traspasando las categorias. Articulo ' . $asin,
                        'success' => false,
                    ];
                    continue;
                }

                $body = [
                    'title' => str_limit($details['title'], 57),
                    'category_id' => $predictor['body']->id,
                    'price' => $details['costo_final_ml'],
                    'currency_id' => 'COP',
                    'available_quantity' => 1,
                    'buying_mode' => 'buy_it_now',
                    'listing_type_id' => 'gold_special',
                    'condition' => 'new',
                    'description' => "<html xmlns='http://www.w3.org/1999/xhtml'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><title>Documento sin t�tulo</title> <style type='text/css'> .center { text-align: justify; font-size: 16px; font-weight: bold; color: #006887; padding:10px 20px} </style> </head> <body> <table width='793' border='0' align='center' cellpadding='0' cellspacing='0'> <tbody><tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/akaes-logo.png' width='793' height='101'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/productos-importados.png' width='793' height='405'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/n-productos.png' width='793' height='131'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/descripcion.png' width='793' height='72'></td> </tr> <tr> <td background='http://acaescolombia.com/PLANTILLAFINAL/fondo-azul.png'> <p class='center'>{$details['feature']}</p> <p class='center'>&nbsp;</p> </td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/barra-color.png' width='793' height='20'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/como-comprar.png' width='793' height='511'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/donde-pagar.png' width='793' height='413'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes1.png' width='793' height='314'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes2.png' width='793' height='169'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes3.png' width='793' height='164'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes4.png' width='793' height='200'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/akaes-footer.png' width='793' height='213'></td> </tr> </tbody></table> </body></html>",
                    'video_id' =>  null,
                    'tags' => [
                                'immediate_payment'
                            ],
                    'warranty' => isset($result->Items->Item->ItemAttributes->Warranty) ? $warranty : 'Con el fabricante',
                    'pictures' => $imageSets,
                ];

                $variations = [
                       [
                           'attribute_combinations' => [],
                            'available_quantity' => 1,
                            'price' => $body['price'],
                            'picture_ids' => [
                                     $imageMain['source']
                                 ]
                       ]
                    ];

                foreach ($atributtes_names as $value) {
                    if ($value = 'Color Primario') {
                        $id = '11000';
                        $color = '2105d8e';
                    }
                    $variations[0]['attribute_combinations'][] = [
                        'id' => '11000',
                        'value_id' => '02bb186'
                    ];
                }

                if (!empty($atributtes_names)) {
                    $body['variations'] = $variations;
                }

                $params = [
                    'access_token' => Credential::find(1)->access_token
                ];

                $response = $meli->post('/items', $body, $params);


                if ($response['body']->status == 400) {
                    $notifications[] = [
                        'content' => 'Mercadolibre devolvio un error en la publicación del producto ' . $asin . ', favor revisar si tiene acceso para publicar en su cuenta de Mercadolibre.',
                        'success' => false,
                    ];
                    continue;
                }

                $item['ml_id'] = $response['body']->id;

                $product = new Product($item);
                $productDetails = new ProductDetail($details);

                $product->saveOrFail();
                $product->detail()->save($productDetails);

                $notifications[] = [
                    'content' => 'La publicacion en Mercadolibre fue realizada con exito. ' . $response['body']->permalink,
                    'success' => true,
                ];
            }

            return $notifications;

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendLinio(Request $request, AmazonAPIManagerContract $amazonAPIManager, LinioAPIManagerContract $linioAPIManager)
    {
        $result = $amazonAPIManager->itemLookup($request->amazonId);

        if (isset($result->Items->Request->Errors)) {
            return redirect()
                ->back()
                ->with('alert', 'La busqueda fue erronea. Amazon devolvió el siguiente error en al busqueda: ' . $result->Items->Request->Errors->Error->Message);
        }

        $asin = $result->Items->Item->ASIN->__toString();

        if (count(Product::isAsinForLinio($asin)->get())) {
            return redirect()
                ->back()
                ->with('alert', 'Este articulo ya fue publicado en Linio');
        }

        $brands = $linioAPIManager->getBrands();

        if (isset($brands['error'])) {
            return redirect()
                ->back()
                ->with('alert', 'Ocurrio un error en la conexión con Linio');
        }

        $existBrand = false;
        foreach ($brands as $brand) {
            if ($brand->getName() == $result->Items->Item->ItemAttributes->Brand->__toString()) {
                $existBrand = true;
            }
        }

        if (!$existBrand) {
            return redirect()
                ->back()
                ->with('alert', 'La marca del producto que esta tratando de publicar no se encuentra registrada en Linio por lo tanto debes hacer una soicitud a Linio de agregar la marca.');
        }
//
        $feature = '';

        foreach ($result->Items->Item->ItemAttributes->Feature as $key => $value) {
            $feature .= $value . '. ';
        }

        if (isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight)) {
            $weight_lbs = (int) $result->Items->Item->ItemAttributes->PackageDimensions->Weight->__toString();
            $weight_lbs = $weight_lbs / 100; //move 2 decimal to the left
            $weight_kls = $weight_lbs / 2.20462;
        }

        if ((Int)$result->Items->Item->Offers->TotalOffers === 0) {
            return redirect()
                ->back()
                ->with('alert', 'La busqueda fue erronea. El articulo que selecciono no tiene ofertas');
        }

        $item = [
            'amazon_id' => $result->Items->Item->ASIN->__toString(),
            // 'ml_id'  => '',
            'li_id'     => '',
            // 'amazon_c'  => '',
            // 'ml_p'      => true,
            'li_p'      => true,
            // 'changed'   => '',
        ];

        $parameters = Parameter::findOrfail(1)->first()->toArray();

        $price = (int) $result->Items->Item->Offers->Offer->OfferListing->Price->Amount->__toString() / 100;

        $ship = $parameters['costo_envio_kg'] * (isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight) ? $weight_kls : $parameters['default_weight']);
        $price_usa = $price + ($price * $parameters['tax_usa']);
        $price_COP = $price_usa * $parameters['TRM'];
        $costo_co = $ship + $price_COP;
        $costo_ut = $costo_co + ($costo_co * $parameters['utilidad']);
        $costo_iva = $costo_ut + ($costo_ut * $parameters['iva_co']);
        $costo_ml = $costo_iva + ($costo_ut * $parameters['comision_meli']);
        $costo_final_ml = (ceil(round($costo_ml, 0, PHP_ROUND_HALF_UP) / 100) * 100);

        $details = [
            'title'          => $result->Items->Item->ItemAttributes->Title->__toString(),
            'img_url'        => isset($result->Items->Item->LargeImage->URL) ? $result->Items->Item->LargeImage->URL->__toString() : null,
            'img_set'        => isset($result->Items->Item->ImageSets->ImageSet[0]) ? $result->Items->Item->ImageSets->ImageSet[0]->LargeImage->URL->__toString() : null,
            'brand'          => $result->Items->Item->ItemAttributes->Brand->__toString(),
            'departament'    => $result->Items->Item->ItemAttributes->Department->__toString(),
            'feature'        => $feature,
            'offer'          => $result->Items->Item->Offers->Offer->OfferListing->OfferListingId->__toString(),
            'weight'         => isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight) ? $weight_kls : $parameters['default_weight'],
            'price'          => $price,
            'costo_co'       => $costo_co,
            'costo_ut'       => $costo_ut,
            'costo_final_ml' => $costo_final_ml,
            // 'costo_final_ml' => '',
        ];

        $imageSets = [];

        $imageMain = [];
        $imageMain['source'] = isset($result->Items->Item->LargeImage->URL) ? $result->Items->Item->LargeImage->URL->__toString() : null;

        $imageSets[] = $imageMain;

        foreach ($result->Items->Item->ImageSets->ImageSet as $image) {
            $array = [];
            $array['source'] = $image->LargeImage->URL->__toString();
            $imageSets[] = $array;
        }

        $details['title'] = GoogleTranslate::translate($details['title']);
        $details['feature'] = GoogleTranslate::translate($details['feature']);

        if (isset($result->Items->Item->ItemAttributes->Warranty)) {
            $warranty = GoogleTranslate::translate($result->Items->Item->ItemAttributes->Warranty->__toString());
        }

        $body = [
            'title' => str_limit($details['title'], 57),
            'category_id' => '11067',
            'price' => $details['costo_final_li'],
            'currency_id' => 'COP',
            'available_quantity' => 1,
            'buying_mode' => 'buy_it_now',
            'listing_type_id' => 'gold_special',
            'condition' => 'new',
            'description' => "<html xmlns='http://www.w3.org/1999/xhtml'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><title>Documento sin t�tulo</title> <style type='text/css'> .center { text-align: justify; font-size: 16px; font-weight: bold; color: #006887; padding:10px 20px} </style> </head> <body> <table width='793' border='0' align='center' cellpadding='0' cellspacing='0'> <tbody><tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/akaes-logo.png' width='793' height='101'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/productos-importados.png' width='793' height='405'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/n-productos.png' width='793' height='131'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/descripcion.png' width='793' height='72'></td> </tr> <tr> <td background='http://acaescolombia.com/PLANTILLAFINAL/fondo-azul.png'> <p class='center'>{$details['feature']}</p> <p class='center'>&nbsp;</p> </td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/barra-color.png' width='793' height='20'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/como-comprar.png' width='793' height='511'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/donde-pagar.png' width='793' height='413'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes1.png' width='793' height='314'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes2.png' width='793' height='169'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes3.png' width='793' height='164'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes4.png' width='793' height='200'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/akaes-footer.png' width='793' height='213'></td> </tr> </tbody></table> </body></html>",
            'video_id' =>  null,
            'tags' => [
                        'immediate_payment'
                    ],
            'warranty' => isset($result->Items->Item->ItemAttributes->Warranty) ? $warranty : 'Con el fabricante',
            'pictures' => $imageSets,
        ];

        $variations = [
               [
                   'attribute_combinations' => [],
                    'available_quantity' => 1,
                    'price' => $body['price'],
                    'picture_ids' => [
                             $imageMain['source']
                         ]
               ]
            ];

        foreach ($atributtes_names as $value) {
            if ($value = 'Color Primario') {
                $id = '11000';
                $color = '2105d8e';
            }
            $variations[0]['attribute_combinations'][] = [
                'id' => '11000',
                'value_id' => '02bb186'
            ];
        }

        if (!empty($atributtes_names)) {
            $body['variations'] = $variations;
        }

        $params = [
            'access_token' => Credential::find(1)->access_token
        ];

        $meli = new Meli(
            config('mercadolibreAPI.credentials.app_id'),
            config('mercadolibreAPI.credentials.secret_key')
        );

        $response = $meli->post('/items', $body, $params);

        if ($response['body']->status === 400) {
            return redirect()
                ->back()
                ->with('alert', 'Mercadolibre devolvio un error en la publicación, favor revisar si tiene acceso para publicar en su cuenta de Mercadolibre.');
        }

        $item['ml_id'] = $response['body']->id;

        $product = new Product($item);
        $productDetails = new ProductDetail($details);

        $product->saveOrFail();
        $product->detail()->save($productDetails);

        $link = \Html::decode("<a href='{$response['body']->permalink}' target='_blank'>{$response['body']->permalink}</a>");

        return redirect()
            ->back()
            ->with('success', 'La publicacion en Mercadolibre fue realizada con exito.')
            ->with('link', $link);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reSendMeli(Request $request, $id, AmazonAPIManagerContract $amazonAPIManager)
    {
        $product = Product::findOrFail($id);

        $result = $amazonAPIManager->itemLookup($product->amazon_id);

        if (isset($result->Items->Request->Errors)) {
            return redirect()
                ->back()
                ->with('alert', 'La busqueda fue erronea. Amazon devolvió el siguiente error en al busqueda: ' . $result->Items->Request->Errors->Error->Message);
        }

        // $asin = $result->Items->Item->ASIN->__toString();

        // if (count(Product::isAsin($asin)->get())) {
        //     return redirect()
        //         ->back()
        //         ->with('alert', 'Este articulo ya fue publicado en Mercadolibre');
        // }

        $feature = '';

        foreach ($result->Items->Item->ItemAttributes->Feature as $key => $value) {
            $feature .= $value . '. ';
        }

        if (isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight)) {
            $weight_lbs = (int) $result->Items->Item->ItemAttributes->PackageDimensions->Weight->__toString();
            $weight_lbs = $weight_lbs / 100; //move 2 decimal to the left
            $weight_kls = $weight_lbs / 2.20462;
        }

        if ((Int)$result->Items->Item->Offers->TotalOffers === 0) {
            return redirect()
                ->back()
                ->with('alert', 'La busqueda fue erronea. El articulo que selecciono no tiene ofertas');
        }

        $item = [
            'amazon_id' => $result->Items->Item->ASIN->__toString(),
            'ml_id'     => '',
            // 'li_id'     => null,
            // db default(false)
            // 'amazon_c'  => '',
            'ml_p'      => true,
            // 'li_p'     => '',
            // 'changed'   => '',
        ];

        if ($result->Items->Item->Offers->Offer->OfferListing->Price->FormattedPrice->__toString() == 'Too low to display') {
            return redirect()
                ->back()
                ->with('alert', 'El articulo que selecciono no tiene ofertas, tiene incongruencias con el precio');
        }

        $parameters = Parameter::findOrfail(1)->first()->toArray();

        $price = (int) $result->Items->Item->Offers->Offer->OfferListing->Price->Amount->__toString() / 100;

        $ship = $parameters['costo_envio_kg'] * (isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight) ? $weight_kls : $parameters['default_weight']);
        $price_usa = $price + ($price * $parameters['tax_usa']);
        $price_COP = $price_usa * $parameters['TRM'];
        $costo_co = $ship + $price_COP;
        $costo_ut = $costo_co + ($costo_co * $parameters['utilidad']);
        $costo_iva = $costo_ut + ($costo_ut * $parameters['iva_co']);
        $costo_ml = $costo_iva + ($costo_ut * $parameters['comision_meli']);
        $costo_final_ml = (ceil(round($costo_ml, 0, PHP_ROUND_HALF_UP) / 100) * 100);

        $details = [
            'title'          => $result->Items->Item->ItemAttributes->Title->__toString(),
            'img_url'        => isset($result->Items->Item->LargeImage->URL) ? $result->Items->Item->LargeImage->URL->__toString() : null,
            'img_set'        => isset($result->Items->Item->ImageSets->ImageSet[0]) ? $result->Items->Item->ImageSets->ImageSet[0]->LargeImage->URL->__toString() : null,
            'brand'          => $result->Items->Item->ItemAttributes->Brand->__toString(),
            'departament'    => $result->Items->Item->ItemAttributes->Department->__toString(),
            'feature'        => $feature,
            'offer'          => $result->Items->Item->Offers->Offer->OfferListing->OfferListingId->__toString(),
            'weight'         => isset($result->Items->Item->ItemAttributes->PackageDimensions->Weight) ? $weight_kls : $parameters['default_weight'],
            'price'          => $price,
            'costo_co'       => $costo_co,
            'costo_ut'       => $costo_ut,
            'costo_final_ml' => $costo_final_ml,
            // 'costo_final_li' => '',

        ];

        $imageSets = [];

        $imageMain = [];
        $imageMain['source'] = isset($result->Items->Item->LargeImage->URL) ? $result->Items->Item->LargeImage->URL->__toString() : null;

        $imageSets[] = $imageMain;

        foreach ($result->Items->Item->ImageSets->ImageSet as $image) {
            $array = [];
            $array['source'] = $image->LargeImage->URL->__toString();
            $imageSets[] = $array;
        }

        $details['title'] = GoogleTranslate::translate($details['title']);
        $details['feature'] = GoogleTranslate::translate($details['feature']);

        if (isset($result->Items->Item->ItemAttributes->Warranty)) {
            $warranty = GoogleTranslate::translate($result->Items->Item->ItemAttributes->Warranty->__toString());
        }

        $meli = new Meli(
            config('mercadolibreAPI.credentials.app_id'),
            config('mercadolibreAPI.credentials.secret_key')
        );

        $ancestor = $result->Items->Item->BrowseNodes;
        while (isset($ancestor)) {
            if (isset($ancestor->BrowseNode->Ancestors)) {
                $ancestor = $ancestor->BrowseNode->Ancestors;
            } else {
                break;
            }
        }

        $content = [
            'title' => str_limit(str_replace(' ', '%', $details['title']), 200),
            // 'category_from' => config('amazonAPI.equivalences.' . $ancestor->BrowseNode->BrowseNodeId->__toString() . '.meli_equivalence'),
            'price' => $details['costo_final_ml'],
            'seller_id' => config('mercadolibreAPI.credentials.user_id')
        ];

        $predictor = $meli->get('/sites/MCO/category_predictor/predict', $content);
        $atributtes = $meli->get('/categories/' . $predictor['body']->id . '/attributes');

        $atributtes_names = array();
        foreach ($atributtes['body'] as $value) {
            if (isset($value->tags->required) && $value->tags->required = true) {
                $atributtes_names[] = $value->name;
            }
        }

        if ($predictor['httpCode'] != 200) {
            return redirect()
                ->back()
                ->with('alert', 'Hubo un problema traspasando las categorias.');
        }

        $body = [
            'title' => str_limit($details['title'], 57),
            'category_id' => $predictor['body']->id,
            'price' => $details['costo_final_ml'],
            'currency_id' => 'COP',
            'available_quantity' => 1,
            'buying_mode' => 'buy_it_now',
            'listing_type_id' => 'gold_special',
            'condition' => 'new',
            'description' => "<html xmlns='http://www.w3.org/1999/xhtml'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><title>Documento sin t�tulo</title> <style type='text/css'> .center { text-align: justify; font-size: 16px; font-weight: bold; color: #006887; padding:10px 20px} </style> </head> <body> <table width='793' border='0' align='center' cellpadding='0' cellspacing='0'> <tbody><tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/akaes-logo.png' width='793' height='101'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/productos-importados.png' width='793' height='405'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/n-productos.png' width='793' height='131'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/descripcion.png' width='793' height='72'></td> </tr> <tr> <td background='http://acaescolombia.com/PLANTILLAFINAL/fondo-azul.png'> <p class='center'>{$details['feature']}</p> <p class='center'>&nbsp;</p> </td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/barra-color.png' width='793' height='20'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/como-comprar.png' width='793' height='511'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/donde-pagar.png' width='793' height='413'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes1.png' width='793' height='314'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes2.png' width='793' height='169'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes3.png' width='793' height='164'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/p-frecuentes4.png' width='793' height='200'></td> </tr> <tr> <td><img src='http://acaescolombia.com/PLANTILLAFINAL/akaes-footer.png' width='793' height='213'></td> </tr> </tbody></table> </body></html>",
            'video_id' =>  null,
            'tags' => [
                        'immediate_payment'
                    ],
            'warranty' => isset($result->Items->Item->ItemAttributes->Warranty) ? $warranty : 'Con el fabricante',
            'pictures' => $imageSets,
        ];

        $variations = [
               [
                   'attribute_combinations' => [],
                    'available_quantity' => 1,
                    'price' => $body['price'],
                    'picture_ids' => [
                             $imageMain['source']
                         ]
               ]
            ];

        foreach ($atributtes_names as $value) {
            if ($value = 'Color Primario') {
                $id = '11000';
                $color = '2105d8e';
            }
            $variations[0]['attribute_combinations'][] = [
                'id' => '11000',
                'value_id' => '02bb186'
            ];
        }

        if (!empty($atributtes_names)) {
            $body['variations'] = $variations;
        }

        $params = [
            'access_token' => Credential::find(1)->access_token
        ];

        $meli = new Meli(
            config('mercadolibreAPI.credentials.app_id'),
            config('mercadolibreAPI.credentials.secret_key')
        );

        $response = $meli->post('/items', $body, $params);

        if ($response['body']->status === 400) {
            return redirect()
                ->back()
                ->with('alert', 'Mercadolibre devolvio un error en la publicación, favor revisar si tiene acceso para publicar en su cuenta de Mercadolibre.');
        }

        $item['ml_id'] = $response['body']->id;

        // $product = new Product($item);
        // $productDetails = new ProductDetail($details);
        // $product->saveOrFail();
        // $product->detail()->save($productDetails);

        $product->update($item);
        $product->detail()->update($details);

        $link = \Html::decode("<a href='{$response['body']->permalink}' target='_blank'>{$response['body']->permalink}</a>");

        return redirect()
            ->back()
            ->with('success', 'La publicacion en Mercadolibre fue realizada con exito.')
            ->with('link', $link);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $params = [
            'access_token' => Credential::find(1)->access_token
        ];

        $meli = new Meli(
            config('mercadolibreAPI.credentials.app_id'),
            config('mercadolibreAPI.credentials.secret_key')
        );

        $body = [
            "status" => "closed"
        ];

        $response = $meli->put('items/' . $product->ml_id, $body, $params);

        if ($response['body']->status === 400) {
            return redirect()
                ->back()
                ->with('alert', 'Mercadolibre devolvio un error cuando se intento cambiar el status del producto');
        }

        if ($product->delete()) {
            return redirect()
            ->back()
            ->with('success', 'El producto ha sido eliminado con éxito, lo puede observar en la lista de productos finalizados en su cuenta de Mercadolibre.');
        }
    }

    /**
     *
     * Find all asin's products in a category or category children
     *
     * @param Arry $request $request->all()
     * @return ''
     */
    public function findAsinByCategory(Request $request, AmazonAPIManagerContract $amazonAPIManager)
    {
        $results = $amazonAPIManager->itemSearch($request->toArray());
        $asinList = array();

        foreach ($results as $result) {
            foreach ($result->Items->Item as $item) {
                $asinList[] = $item->ASIN->__toString();
            }
        }

        $request->request->add(['_list' => $asinList]);

        $notifications = $this->sendMeliMasive($request, $amazonAPIManager);

        $thisSearch = [
            'search'      => isset($request->search) ? $request->search : str_replace($request->root(), '', $request->fullUrl()),
            'page'        => isset($request->page) ? ($request->page + 1) : 2,
            'total_pages' => round((int) $results[0]->Items->TotalPages / (config('amazonAPI.credentials.results_per_page') / 10)),
            'keywords'    => $request->keywords,
            'category'    => $request->category,
            'prime'       => $request->prime,
            'node'        => $request->node,
            'child'       => $request->child,
            'meli'        => true,
            'linio'       => false,
        ];

        $search = AmazonSearch::where('search', $thisSearch['search'])->first();

        if (is_null($search)) {
            $search = AmazonSearch::create($thisSearch);
            Alert::create( [
                'description' => 'Se he creado un nuevo envio masivo con el valor: ' . $thisSearch['search'],
            ]);
        } else {
            if (isset($search->page) && $search->page >= $thisSearch['page']) {
                Alert::create( [
                    'description' => 'No se ha podido crear el envio masivo: ' . $thisSearch['search'] . ', debido a que ya existia.',
                ]);
            } else {
                $search->update($thisSearch);
                Alert::create( [
                    'description' => 'Se ha realizado una nueva publicación masiva: ' . $thisSearch['search'] . '.',
                ]);
            }
        }


        if (debug_backtrace()[1]['function'] == 'searchesHandler') {
            return $notifications;
        }

        return redirect()
            ->back()
            ->with('success', 'El envio masivo fue registrado exitósamente.');
    }

    /**
     *
     * Get one search at time and process it
     *
     * @param null
     * @return
     */
    public function searchesHandler(Request $request, AmazonAPIManagerContract $amazonAPIManager)
    {
        $search = AmazonSearch::first();

        if (is_null($search)) {
            return http_response_code(200);
        }

        if ($search->page >= $search->total_pages) {
            Alert::create( [
                'description' => 'La publicación masiva: ' . $search->search . ' fue culminada.',
            ]);
            $search->delete();
        }

        $request->request->add($search->toArray());
        $notifications = $this->findAsinByCategory($request, $amazonAPIManager);

        $count = 0;

        foreach ($notifications as $notification) {
            if ($notification['success'] == true) {
                $count++;
            }
        }

        Alert::create( [
            'description' => 'Se procesaron ' . $count . ' artículos, en el ultimo envio masivo, ' . (count($notifications) - $count) . ' no se pudieron procesar.',
        ]);


        dd($notifications, $count);
    }
}
