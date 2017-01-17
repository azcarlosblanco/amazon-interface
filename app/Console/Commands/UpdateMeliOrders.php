<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Entities\Product;
use App\Entities\Parameter;
use App\Entities\MeliUpdate;
use App\Entities\Credential;
use App\Components\MercadolibreAPI\Meli\meli as Meli;
use App\Components\AmazonAPI\Contracts\AmazonAPIManagerContract;

class UpdateMeliOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 're-calcule the price for all products and update them';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * Get the first record eloquent can find
     *
     * @return mixed
     */
    public function handle(AmazonAPIManagerContract $amazonAPIManager)
    {
        if (!empty($order = MeliUpdate::first())) {
            $product = Product::with('detail')
                ->where('ml_id', $order->meli_id)
                ->first();

            $weight_kls = $product->detail->weight;

            $parameters = Parameter::findOrfail(1)->first()->toArray();

            $price = (float) $product->detail->price;

            $ship = $parameters['costo_envio_kg'] * (!is_null($weight_kls) ? $weight_kls : $parameters['default_weight']);
            $price_usa = $price + ($price * $parameters['tax_usa']);
            $price_COP = $price_usa * $parameters['TRM'];
            $costo_co = $ship + $price_COP;
            $costo_ut = $costo_co + ($costo_co * $parameters['utilidad']);
            $costo_iva = $costo_ut + ($costo_ut * $parameters['iva_co']);
            $costo_ml = $costo_iva + ($costo_ut * $parameters['comision_meli']);
            $costo_final_ml = (ceil(round($costo_ml, 0, PHP_ROUND_HALF_UP) / 100) * 100);

            $params = [
                'access_token' => Credential::find(1)->access_token
            ];

            $meli = new Meli(
                config('mercadolibreAPI.credentials.app_id'),
                config('mercadolibreAPI.credentials.secret_key')
            );

            $body = [
                "price" => $costo_final_ml
            ];

            $response = $meli->put('items/' . $product->ml_id, $body, $params);

            if ($response['httpCode'] == 400
                && isset($response['body']->cause[0]->code)
                && $response['body']->cause[0]->code == 'item.price.not_modifiable') {
                    $order->delete();
                    echo 1;
            }

            if ($response['httpCode'] == 200) {
                $order->delete();
                echo 2;
            }

        }

        echo 3;

    }
}
