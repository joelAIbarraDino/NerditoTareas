<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $token = config('services.mercadopago.token');

        if(empty($token))
            return;

        try{
            MercadoPagoConfig::setAccessToken($token);
        }catch(Exception $e){
            Log::error('Error al configurar Mercado Pago: '. $e->getMessage());
        }

    }
}
