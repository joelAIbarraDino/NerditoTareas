<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AfterLoginController extends Controller
{
    public function handle(Request $request){
        $user = $request->user();

        if(!$user)
            return redirect()->route('login');

        if($user->hasRole('admin'))
            return redirect()->route('dashboard');

        if($user->hasRole('specialist'))
            return redirect()->route('specialist.index');

        return redirect()->route('client.index');
    }
}
