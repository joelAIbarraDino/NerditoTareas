<?php

namespace App\Http\Controllers;

use App\Enums\EducationalLevel;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('users/admin/client/index', [
            'clients'=>Client::with('user')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('users/admin/client/create', [
            'educationalLevel'=>EducationalLevel::options()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'educational_level'=>['required', new Enum(EducationalLevel::class)],
            'email'=>'required|email|unique:users,email',
            'whatsapp'=>['required', 'regex:/^[0-9]{10}$/', 'unique:users,whatsapp'],
            'password'=>['required', 'confirmed', Password::default()]
        ]);

        DB::transaction(function() use($request){
            $user = User::create($request->except(['educational_level']));
            $user->assignRole('client');

            $client = new Client;

            $client->id_user = $user->id;
            $client->educational_level = $request->educational_level;
            $client->save();
        });
        
        return redirect()->route('clients.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return Inertia::render('users/admin/client/update', [
            'client'=>$client->with('user')->first(),
            'educationalLevel'=>EducationalLevel::options(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validate = $request->validate([
            'name'=>'required|string',
            'educational_level'=>['required', new Enum(EducationalLevel::class)],
            'email'=>[
                'required',
                'email',
                Rule::unique('users')->ignore($client->id_user),
            ],
            'whatsapp'=>[
                'required', 
                'regex:/^[0-9]{10}$/',
                Rule::unique('users')->ignore($client->id_user),
            ]
        ]);
        
        $user = User::find($client->id_user);

        DB::transaction(function() use($request, $client, $user){
            $client->educational_level = $request->educational_level;
            $client->save();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->whatsapp = $request->whatsapp;

            $user->save();
        });

        return redirect()->route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        if($client->homework()->count() > 0){
            return redirect()->route('clients.index')->with('message', 'El cliente tiene tareas registradas, no se puede eliminar');
        }

        $user = User::find($client->id_user);

        DB::transaction(function() use ($user, $client){
            $client->delete();

            $user->removeRole('client');
            $user->delete();
        });

        return redirect()->route('clients.index')->with('message', 'Cliente eliminado con exit');
    }
}
