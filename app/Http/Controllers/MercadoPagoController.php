<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use Illuminate\Http\Request;
use Inertia\Inertia;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class MercadoPagoController extends Controller
{

    public function show(Homework $homework){
        return Inertia::render('users/admin/paymentOrder/create', [
            'homework'=>$homework,
        ]);
    }

    public function generatePaymentLink(Request $request, Homework $homework)
    {
        $pendingAmount = $homework->final_price - $homework->amount_paid;

        $request->validate([
            'amount'=>[
                'required',
                'numeric',
                'max:'.$pendingAmount,
            ],
        ]);

        $client = new PreferenceClient();

        try{
            $preference = $client->create([
                "items"=>[
                    [
                        "id"=> (string) $homework->id,
                        "title" => "Pago tarea #". $homework->order_id,
                        'quantity'=>1,
                        "unit_price"=>(float)$request->amount,
                        "currency_id"=>"MXN"
                    ]
                ],
                "external_reference" => (string)$homework->order_id
            ]);

            dd($preference);

        }catch(MPApiException $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
