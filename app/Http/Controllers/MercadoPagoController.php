<?php

namespace App\Http\Controllers;

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use MercadoPago\MercadoPagoConfig;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;

use App\Models\OrderPayment;
use App\Models\Payment;
use App\Models\Homework;
use Exception;

class MercadoPagoController extends Controller
{
    public function generatePaymentLink(Request $request, Homework $homework)
    {
        
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

        $pendingAmount = $homework->final_price - $homework->amount_paid;

        $request->validate([
            'amount'=>['required', 'numeric', 'min:10', 'max:'.$pendingAmount],
        ]);

        $client = new PreferenceClient;

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
                "external_reference" => (string)$homework->order_id,
                "binary_mode" => true,
                "expire"=>true,
                "expire_date_from" => now()->toISOString(),
                "expire_date_to" => now()->addDays(1)->toISOString(),
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

    public function payment(Request $request, Homework $homework)
    {
        $request->validate([
            'token' => 'required|string',
            'payment_method_id' => 'required|string',
            'transaction_amount' => 'required|numeric',
            'payer.email' => 'required|email',
        ]);

        $client = new PaymentClient;

        try{
            $payment = $client->create([
                "token" => $request->token,
                "issuer_id" => $request->issuer_id,
                "payment_method_id" => $request->payment_method_id,
                "transaction_amount" => (float)$request->transaction_amount,
                "installments" => $request->installments,
                "payer" => [
                    "email" => $request->payer['email'],
                ],
                "external_reference" => (string)$homework->order_id,
            ]);

            if($payment->status === 'approved'){
                Payment::create([
                    'order_id'=>$homework->id,
                    'amount'=> (float)$request->transaction_amount,
                    'payment_id'=>$payment->id,
                    'method'=> PaymentMethod::DebitCard,
                    'status'=>PaymentStatus::Approved,
                ]);

                return redirect()->route('client.index')->with('message', 'Pago aceptado :');
            }

            return back()->withErrors(['pago' => 'El pago fue ' . $payment->status]);


        }catch(MPApiException $e){
            return back()->withErrors([
                'pago' => 'Error al intentar procesar el pago'
            ]);
        }catch(Exception $e){
            return back()->withErrors([
                'pago' => 'Ocurrio un error al intentar procesar el pago'
            ]);
        }

    }

    public function webhook(Request $request)
    {
        $paymentID = $request->data['id'];
        if(!$paymentID) return response()->json(['message'=>'No ID provided'], 400);

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);

        try{

            $client = new PaymentClient;
            $mpPayment = $client->get($paymentID);

            $paymentExists = Payment::where('payment_id', $mpPayment->id)->exists();

            if($paymentExists)
                return response()->json(['message' => 'Este pago ya fue procesado'], 200);

            $order_id = $mpPayment->external_reference;
            $homework = Homework::where('order_id', $order_id)->first();
            if(!$homework) return response()->json(['message' => 'Order not found'], 404);
            
            $orderPayment = OrderPayment::where('id_homework', $homework->id);

            $status = match($mpPayment->status) {
                'approved' => PaymentStatus::Approved,
                'reject' => PaymentStatus::Reject,
                default => PaymentStatus::Pending
            };

            $methodPayment = PaymentMethod::tryFrom($mpPayment->payment_type_id) ?? PaymentMethod::AccountMoney;

            DB::transaction(function () use ($homework, $orderPayment, $mpPayment, $status, $methodPayment){
                Payment::create([
                    'order_id' => $homework->id,
                    'amount' => $mpPayment->transaction_amount,
                    'payment_id' => $mpPayment->id,
                    'status' => $status,
                    'method' => $methodPayment
                ]);


                if($status === PaymentStatus::Approved){
                    $homework->increment('amount_paid', $mpPayment->transaction_amount);
                    if($orderPayment){
                        $orderPayment->update([
                            'status' => PaymentStatus::Approved,
                        ]);
                    }

                }

            });

            return response()->json(['message' => 'Orden procesada con exito']);

        }catch(MPApiException $e){

            dd($e->getApiResponse());
            Log::error('Error webhook MP: '. $e->getMessage());
            return response()->json(['error' => 'MP error:'. $e->getMessage()], 400);
        }catch(Exception $e){
            Log::error('Error webhook MP: '. $e->getMessage());
            return response()->json(['error' => 'Internal error:'. $e->getMessage()], 400);
        }
    }
}
