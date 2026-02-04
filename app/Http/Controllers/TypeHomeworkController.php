<?php

namespace App\Http\Controllers;

use App\Models\TypeHomework;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TypeHomeworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('users/admin/typeHomework/index', [
            'typesHomework'=>TypeHomework::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('users/admin/typeHomework/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
        ]);

        TypeHomework::create($request->all());

        return redirect()->route('type-homework.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeHomework $type_homework)
    {
        return Inertia::render('users/admin/typeHomework/update',[
            'typeHomework' => $type_homework,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypeHomework $type_homework)
    {
        $request->validate([
            'name'=> 'required|string',
        ]);

        $type_homework->name = $request->name;
        $type_homework->save();

        return redirect()->route('type-homework.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeHomework $type_homework)
    {
        if($type_homework->homework()->count() > 0){
            return redirect()->route('type-homework.index')->with('message', 'El tipo de tarea esta asignado en otras tareas, no se puede eliminar');
        }

        $type_homework->delete();

        return redirect()->route('type-homework.index');
    }
}
