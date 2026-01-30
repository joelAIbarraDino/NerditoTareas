<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use App\Models\SpecialistArea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * Display the specified resource.
     */
    public function show(Specialist $specialist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialist $specialist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialist $specialist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialist $specialist)
    {
        //
    }
}
