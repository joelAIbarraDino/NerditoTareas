<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileSpecialistController extends Controller
{
    public function index(){
        return Inertia::render('users/specialist/Profile');
    }
}
