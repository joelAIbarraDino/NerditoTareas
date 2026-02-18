<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Homework;
use App\Models\OrderPayment;
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
            'amount'=>['required', 'numeric', 'max:'.$pendingAmount],
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


            OrderPayment::create([
                'id_homework' => $homework->id,
                'amount' => $request->amount,
                'mp_link' => $preference->sandbox_init_point,
                'status' => PaymentStatus::Pending,
            ]);

            return back()->with('message', 'Orden de pago generada correctamente');
            

        }catch(MPApiException $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
