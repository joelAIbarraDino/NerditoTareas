<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileAdminController extends Controller
{
    public function index(){
        return Inertia::render('users/admin/Dashboard');
    }
}
