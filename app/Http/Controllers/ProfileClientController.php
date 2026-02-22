<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Homework;
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
}
