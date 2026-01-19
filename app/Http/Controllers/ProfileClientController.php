<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileClientController extends Controller
{
    public function index(){
        return Inertia::render('users/client/Profile');
    }
}
