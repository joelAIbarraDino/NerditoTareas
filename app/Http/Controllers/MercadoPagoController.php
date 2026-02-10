<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use Illuminate\Http\Request;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class MercadoPagoController extends Controller
{
    public function generatePaymentLink(Homework $homework)
    {
        // $client = new PreferenceClient();

        // try{
        //     $preference = $client->create([
        //         "items"=>[
        //             [
        //                 "id"=> (string) $homework->id,
        //                 "title" => "Pago tarea #". $homework->order_id,
        //                 'quantity'=>1,
        //                 "unit_price"=>(float)$homework->final_price,
        //                 "currency_id"=>"MXN"
        //             ]
        //         ],
        //         "external_reference" => (string)$homework->order_id
        //     ]);

        //     return response()->json([
        //         // En pruebas usa sandbox_init_point, en producción init_point
        //         'url' => $preference->sandbox_init_point 
        //     ]);

        // }catch(MPApiException $e){
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }
    }
}
