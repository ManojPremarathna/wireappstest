<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicationRequest;
use App\Http\Requests\UpdateMedicationRequest;
use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true,
            'data' => Medication::all(),
            'message' => 'Successful'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicationRequest $request)
    {
        $medication = Medication::create([
            'name' => $request->name,
            'description' => ($request->description) ?? '',
            'qty' => ($request->qty) ?? 0,
            'created_by' => Auth::id()
        ]);

        return response([
            'status' => true,
            'message' => 'Successfuly saved',
            'data' => $medication
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Medication $medication)
    {
        return response([
            'status' => true,
            'data' => $medication,
            'message' => 'Successful'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicationRequest $request, Medication $medication)
    {
        $medication->update([
            'name' => $request->name,
            'description' => ($request->description) ?? '',
            'qty' => ($request->qty) ?? 0,
            'updated_by' => Auth::id()
        ]);

        return response([
            'status' => true,
            'message' => 'Successfuly updated',
            'data' => $medication
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medication $medication)
    {
        if(Auth::user()->can('medication.destroy')) {
            if (Auth::user()->can('medication.delete')) {
                $medication->forcedelete();
            } elseif (Auth::user()->can('medication.destroy')) {
                $medication->update(['deleted_by' => Auth::id()]);
                $medication->delete();
            } 
    
            return response([
                'status' => true,
                'message' => 'Successfuly deleted',
                'data' => []
            ], 200);
        }

        return response([
            'status' => false,
            'message' => 'You dont have access to delete',
            'errors' => 'You dont have access to delete'
        ], 401);
    }

}
