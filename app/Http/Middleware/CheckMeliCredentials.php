<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Entities\Credential;
use App\Components\MercadolibreAPI\Meli\meli as Meli;

class CheckMeliCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $meliCredentials = Credential::findOrFail(1);

        if ($meliCredentials->updated_at->diffInMinutes(Carbon::now('America/Bogota')) >= 360) {

            $meli = new Meli(
                config('mercadolibreAPI.credentials.app_id'),
                config('mercadolibreAPI.credentials.secret_key')
            );

            $params = [
                'grant_type' => 'refresh_token',
                'client_id' => config('mercadolibreAPI.credentials.app_id'),
                'client_secret' => config('mercadolibreAPI.credentials.secret_key'),
                'refresh_token' => $meliCredentials->refresh_token,
            ];

            $response = $meli->post('/oauth/token', $body = null, $params);

            if ($response['httpCode'] !== 200) {
                return redirect()
                    ->back()
                    ->with('alert', 'Error actualiando credenciales en Mercadolibre');
            }

            $meliCredentials->update([
                    'access_token' => $response['body']->access_token,
                    'refresh_token' => $response['body']->refresh_token,
                    'user_id' => $response['body']->user_id,
                ]);
        }

        return $next($request);
    }
}
