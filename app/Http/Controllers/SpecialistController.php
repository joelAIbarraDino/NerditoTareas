<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use App\Models\SpecialistArea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Validation\Rules\Password;

class SpecialistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('users/admin/specialist/index', [
            'specialists' => Specialist::with(['user', 'specialistArea'])->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('users/admin/specialist/create', [
            'specialistAreas' => SpecialistArea::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'area'=>'required|integer|exists:specialist_areas,id',
            'email'=>'required|email|unique:users,email',
            'whatsapp'=>['required', 'regex:/^[0-9]{10}$/', 'unique:users,whatsapp'],
            'password'=>['required', 'confirmed', Password::default()]
        ]);

        
        $user = User::create($request->only(['name', 'email', 'whatsapp', 'password']));
        $user->assignRole('specialist');

        $specialist = new Specialist;

        $specialist->id_user = $user->id;
        $specialist->area = $request->area;
        $specialist->save();
    

        return redirect()->route('specialists.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialist $specialist)
    {
        return Inertia::render('users/admin/specialist/update', [
            'specialist'=>$specialist->with(['user', 'specialistArea'])->first(),
            'specialistAreas' => SpecialistArea::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialist $specialist)
    {
        $request->validate([
            'name'=>'required|string',
            'area'=>'required|integer|exists:specialist_areas,id',
            'email'=>[
                'required',
                'email',
                Rule::unique('users')->ignore($specialist->id_user),
            ],
            'whatsapp'=>[
                'required', 
                'regex:/^[0-9]{10}$/',
                Rule::unique('users')->ignore($specialist->id_user),
            ],
            'password'=>['nullable', 'confirmed', Password::default()]
        ]);

        $user = User::find($specialist->id_user);

        DB::transaction(function() use($request, $specialist, $user){
            $specialist->area = $request->area;
            $specialist->save();
            
            $user->name = $request->name;
            $user->email = $request->email;
            $user->whatsapp = $request->whatsapp;

            if(!empty($request->password))
                $user->password = Hash::make($request->password);

            $user->save();

        });

        return redirect()->route('specialists.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialist $specialist)
    {

        if($specialist->homework()->count() > 0){
            return redirect()->route('specialists.index')->with('messagge', 'El especialista tiene tareas asignadas, no se puede eliminar');   
        }

        if($specialist->paymentSpecialists()->count() > 0){
            return redirect()->route('specialists.index')->with('messagge', 'El especialista tiene pagos registrados, no se puede eliminar');
        }

        $user = User::find($specialist->id_user);

        DB::transaction(function() use($user, $specialist){
            $specialist->delete();
            
            $user->removeRole('specialist');
            $user->delete();
        });

        return redirect()->route('specialists.index')->with('message', 'Especialista eliminado con exito');

    }
}
