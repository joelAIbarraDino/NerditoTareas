<?php

namespace App\Http\Controllers;

use App\Enums\ConversionOrigin;
use App\Enums\HomeworkChange;
use App\Enums\HomeworkStatus;
use App\Models\Client;
use App\Models\Homework;
use App\Models\Specialist;
use App\Models\TypeHomework;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class HomeworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('users/admin/homework/index', [
            'homework'=>Homework::with(['admin', 'client.user', 'specialist.user', 'typeHomework', 'payments', 'orderPayments'])->get(),
            'specialists'=>Specialist::with('user')->get(),
            'changeStatus'=>HomeworkChange::options(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('users/admin/homework/create', [
            'typesHomework' => TypeHomework::all(),
            'specialists'=>Specialist::with('user')->get(),
            'clients'=>Client::with('user')->get(),
            'conversions'=>ConversionOrigin::options(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'description'=>'required|string',
            'client'=>'required|integer|exists:clients,id',
            'conversion'=>['required', new Enum(ConversionOrigin::class)],
            'specialist'=>'nullable|integer|exists:specialists,id',
            'type_homework'=>'required|integer|exists:type_homework,id',
            'client_delivery'=>'required|string',
            'specialist_delivery'=>'required|string',
            'final_price'=>'required|numeric|min:0',
            'specialist_payment'=>'required|numeric|min:0.0|lte:final_price',
        ]);


        $homework = new Homework;

        $homework->name = $request->name;
        $homework->description = $request->description;
        $homework->client = $request->client;
        $homework->conversion = $request->conversion;
        $homework->type_homework = $request->type_homework;
        $homework->client_delivery = $request->client_delivery;
        $homework->specialist_delivery = $request->specialist_delivery;
        $homework->specialist = $request->specialist;
        $homework->final_price = $request->final_price;
        $homework->specialist_payment = $request->specialist_payment;
        $homework->amount_paid = 0;
        $homework->proft = $request->final_price - $request->specialist_payment;


        $homework->status = $request->filled('specialist') ? HomeworkStatus::Assigned : HomeworkStatus::Unassigned;
        $homework->change = HomeworkChange::NoChange;
        $homework->order_id = $this->generarCodigoControl();
        $homework->admin = Auth::id();

        $homework->save();

        $homework->private_order_id = $this->generarCodigoControlInterno($homework->id);
        $homework->save();

        return redirect()->route('homework.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Homework $homework)
    {
        if($homework->payments()->count() > 0)
            return redirect()->route('homework.index')->with('message', 'Esta tarea tiene un pago registrado, no se puede eliminar');

        if($homework->status === HomeworkStatus::Assigned)
            return redirect()->route('homework.index')->with('message', 'Esta tarea esta asignado a un especialista, no se puede eliminar');


        $homework->delete();

        return redirect()->route('homework.index');
    }

    public function assign(Request $request, Homework $homework){
        $request->validate([
            'specialist_id' => ['nullable', 'integer', Rule::exists('specialists', 'id')],
        ]);
        
        $currentHomeworkStatus = HomeworkStatus::from($homework->status);

        $status = !is_null($request->specialist_id) ? 
            match($currentHomeworkStatus){
                HomeworkStatus::Unassigned => HomeworkStatus::Assigned,
                default => $homework->status,
            } : HomeworkStatus::Unassigned;
    
        $homework->update([
            'specialist' => $request->specialist_id,
            'status' => $status,
        ]);

        return back()->with('message', 'Tarea asignada correctamente');
    }

    public function changeStatus(Request $request, Homework $homework){
        $request->validate([
            'changeStatus'=>['required', new Enum(HomeworkChange::class)],
        ]);

        $changeStatus = HomeworkChange::from($request->changeStatus);

        $statusFinal = match($changeStatus){
            HomeworkChange::NoChange => HomeworkStatus::Completed,
            HomeworkChange::InChange => HomeworkStatus::InChanges,
            HomeworkChange::ChangeReady => HomeworkStatus::Completed,
            HomeworkChange::ChangeDelivered => HomeworkStatus::Delivered,
            HomeworkChange::ChangeRequested => HomeworkStatus::InChanges,
        };

        $homework->update([
            'change' => $request->changeStatus,
            'status' => $statusFinal,
        ]);

        return back()->with('message', 'Status cambiado correctamente');
    }

    public function generarCodigoControl()
    {
        $prefijo = 'NTC-';
        
        // Generamos 10 dígitos aleatorios
        $numeros = '';
        for ($i = 0; $i < 10; $i++) {
            $numeros .= random_int(0, 9);
        }

        return $prefijo . $numeros;
    }

    public function generarCodigoControlInterno($id)
    {
        $prefijo = 'NT-';
        
        return $prefijo . str_pad($id, 5, '0', STR_PAD_LEFT);
    }
}
