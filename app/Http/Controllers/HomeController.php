<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\MercadolibreAPI\Meli\meli as Meli;
use App\Entities\Cart;
use App\Entities\Order;
use App\Entities\Product;
use App\Entities\Credential;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $productsMeli = Product::with('detail')->where('ml_p', true)->orderBy('created_at', 'DESC')->get();
        $productsLinio = Product::with('detail')->where('li_p', true)->orderBy('created_at', 'DESC')->get();
        $orders = Order::where('processed', false)->orderBy('created_at', 'DESC')->get();
        $carts = Cart::where('processed', false)->orderBy('created_at', 'DESC')->get();
        $rejects = Order::where('rejected', true)->orderBy('created_at', 'DESC')->get();


        // $meli = new Meli(
        //     config('mercadolibreAPI.credentials.app_id'),
        //     config('mercadolibreAPI.credentials.secret_key')
        // );
        //
        // if (!$request->hasCookie('meli') && !$request->has('code')) {
        //     $redirectUrl = $meli->getAuthUrl(
        //         config('mercadolibreAPI.credentials.callback'),
        //         Meli::$AUTH_URL['MCO']
        //     );
        //     return redirect($redirectUrl);
        // }
        //
        // if ($request->has('code')) {
        //     $user = $meli->authorize(
        //         $request->code,
        //         config('mercadolibreAPI.credentials.callback')
        //     );
        //
        //     if (isset($user['body']->error)) {
        //         \Auth::logout();
        //         return redirect('/iniciar-sesion');
        //     }
        //
        //     if ($user['body']->user_id != config('mercadolibreAPI.credentials.user_id')) {
        //         \Auth::logout();
        //         return redirect('/iniciar-sesion')
        //             ->with('alert', 'El usuario con el que se esta tratando de autentificar en mercadolibre no es correcto');
        //     }
        //
        //     $meliCredentials = Credential::find(1)
        //         ->update([
        //             'access_token' => $user['body']->access_token,
        //             'refresh_token' => $user['body']->refresh_token,
        //             'user_id' => $user['body']->user_id,
        //         ]);
        //
        //     return response(view('home.main', compact('productsMeli', 'productsLinio', 'orders', 'carts', 'rejects')))
        //         ->cookie(
        //             'meli', $user, 300
        //         );
        //
        // }

        // $params = [
        //     'grant_type' => 'refresh_token',
        //     'client_id' => config('mercadolibreAPI.credentials.app_id'),
        //     'client_secret' => config('mercadolibreAPI.credentials.secret_key'),
        //     'refresh_token' => \Cookie::get('meli')['body']->refresh_token,
        // ];
        //
        // $response = $meli->post('/oauth/token', $body = null, $params);
        //
        // dd($response);


        // \Cookie::queue(\Cookie::forget('meli'));
         return view('home.main', compact('productsMeli', 'productsLinio', 'orders', 'carts', 'rejects'));
    }

    /**
     *
     * Renovate meli token
     *
     * @param Request $request
     * @return {11:return type}
     */
    public function renovateMeliToken(Request $request)
    {
        $meli = new Meli(
            config('mercadolibreAPI.credentials.app_id'),
            config('mercadolibreAPI.credentials.secret_key')
        );

        if (!$request->hasCookie('meli') && !$request->has('code')) {
            $redirectUrl = $meli->getAuthUrl(
                config('mercadolibreAPI.credentials.callback'),
                Meli::$AUTH_URL['MCO']
            );
            return redirect($redirectUrl);
        }

        if ($request->has('code')) {
            $user = $meli->authorize(
                $request->code,
                config('mercadolibreAPI.credentials.callback')
            );

            if (isset($user['body']->error)) {
                \Auth::logout();
                return redirect('/iniciar-sesion');
            }

            if ($user['body']->user_id != config('mercadolibreAPI.credentials.user_id')) {
                \Auth::logout();
                return redirect('/iniciar-sesion')
                    ->with('alert', 'El usuario con el que se esta tratando de autentificar en mercadolibre no es correcto');
            }

            $meliCredentials = Credential::find(1)
                ->update([
                    'access_token' => $user['body']->access_token,
                    'refresh_token' => $user['body']->refresh_token,
                    'user_id' => $user['body']->user_id,
                ]);

                \Cookie::make('meli', $user, 300);

                return redirect()->route('home');
        }

    }
}
