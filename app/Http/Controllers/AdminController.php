<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('users/admin/admin/index', [
            'admins'=>User::role('admin')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('users/admin/admin/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'whatsapp'=>[ 'required', 'regex:/^[0-9]{10}$/' ],
            'password'=>['required', 'confirmed', Password::default()]
        ]);

        $user = User::create($request->all());

        $user->assignRole('admin');

        return redirect()->route('admins.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        return Inertia::render('users/admin/admin/update', [
            'admin'=>$admin,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name'=>'required|string',
            'whatsapp'=>[ 'required', 'regex:/^[0-9]{10}$/' ],
            'password'=>['nullable', 'confirmed', Password::default()]
        ]);

        $admin->name = $request->name;
        $admin->whatsapp = $request->whatsapp;

        if(!empty($request->password))
            $admin->password = Hash::make($request->password);

        $admin->save();

        return redirect()->route('admins.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        if($admin->homework()->count() > 0){
            return redirect()->route('admins.index')->with('message', 'El administrador tiene tareas registradas por el');
        }

        $admin->delete();

        return redirect()->route('admins.index')->with('message', 'Adminstrador eliminado con exito');
    }
}
