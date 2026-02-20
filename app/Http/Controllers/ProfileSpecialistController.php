<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileSpecialistController extends Controller
{
    public function index(){
        $specialist = Specialist::where('id_user', Auth::id())->get()->first();
        return Inertia::render('users/specialist/Profile', [
            'homework' => Homework::with('typeHomework')->where('specialist', $specialist->id)->get(),
        ]);
    }
}
