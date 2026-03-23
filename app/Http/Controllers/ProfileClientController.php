<?php

namespace App\Http\Controllers;

use App\Enums\HomeworkChange;
use App\Enums\HomeworkStatus;
use App\Models\Client;
use App\Models\Homework;
use App\Models\TypeHomework;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileClientController extends Controller
{
    public function index(){
        $client = Client::where('id_user', Auth::id())->get()->first();
        return Inertia::render('users/client/Profile', [
            'homework' => Homework::with('typeHomework')->where('client', $client->id)->get(),
        ]);
    }

    public function homeworkPayment(string $order_id){

        $homework = Homework::where('order_id', $order_id)->get()->first();

        return Inertia::render('users/client/payment/payment', [
            'homework' => $homework
        ]);
    }

    public function createHomework(){
        return Inertia::render('users/client/homework/create', [
            'typesHomework' => TypeHomework::all(),
        ]);
    }

    public function saveHomework(Request $request){
        $request->validate([
            'name'=>'required|string',
            'description'=>'required|string',
            'type_homework'=>'required|integer|exists:type_homework,id',
            'client_delivery'=>'required|string',
        ]);


        $homework = new Homework;

        $homework->name = $request->name;
        $homework->description = $request->description;
        $homework->client = $request->client;
        $homework->type_homework = $request->type_homework;
        $homework->client_delivery = $request->client_delivery;
        
        $homework->specialist_delivery = $request->specialist_delivery;
        $homework->specialist = $request->specialist;
        $homework->final_price = $request->final_price;
        $homework->specialist_payment = $request->specialist_payment;
        $homework->amount_paid = 0;
        $homework->proft = $request->final_price - $request->specialist_payment;
        $homework->order_id = $this->generarCodigoControl();
        $homework->status = HomeworkStatus::Unassigned;
        $homework->change = HomeworkChange::NoChange;
        $homework->admin = Auth::id();

        $homework->save();

        return redirect()->route('homework.index');
    }

    public function generarCodigoControl()
    {
        $code = "";
        $prefijo = 'NTC-';
        
        $numeros = '';

        for ($i = 0; $i < 10; $i++)
            $numeros .= random_int(0, 9);
        
        $code =  $prefijo . $numeros;

        while(Homework::where('order_id', $code)->exit()){
            $numeros = '';
            for ($i = 0; $i < 10; $i++)
                $numeros .= random_int(0, 9);

            $code =  $prefijo . $numeros;
        }

        return $code;
    }
}
