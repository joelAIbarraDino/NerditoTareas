<?php

namespace App\Http\Controllers;

use App\Models\SpecialistArea;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpecialistAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('users/admin/specialistArea/index', [
            'specialistAreas' => SpecialistArea::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('users/admin/specialistArea/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
        ]);

        SpecialistArea::create($request->all());

        return redirect()->route('specialist-area.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpecialistArea $specialist_area)
    {
        return Inertia::render('users/admin/specialistArea/update', [
            'specialist_area'=>$specialist_area,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpecialistArea $specialist_area)
    {
        $request->validate([
            'name'=> 'required|string',
        ]);

        $specialist_area->name = $request->name;

        $specialist_area->save();

        return redirect()->route('specialist-area.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecialistArea $specialist_area)
    {
        if($specialist_area->specialists()->count() > 0){
            return redirect()->route('specialist-area.index')->with('message', 'Hay un especialista con esta area asignado, no se puede eliminar');
        }

        $specialist_area->delete();

        return redirect()->route('specialist-area.index');
    }
}
