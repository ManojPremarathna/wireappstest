<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true,
            'data' => Customer::all(),
            'message' => 'Successful'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create([
            'name' => $request->name,
            'email' => ($request->email) ?? '',
            'phone' => ($request->phone) ?? '',
            'created_by' => Auth::id()
        ]);

        return response([
            'status' => true,
            'message' => 'Successfuly saved',
            'data' => $customer
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return response([
            'status' => true,
            'data' => $customer,
            'message' => 'Successful'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update([
            'name' => $request->name,
            'email' => ($request->email) ?? '',
            'phone' => ($request->phone) ?? '',
            'updated_by' => Auth::id()
        ]);

        return response([
            'status' => true,
            'message' => 'Successfuly updated',
            'data' => $customer
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if(Auth::user()->can('customer.destroy')) {
            if (Auth::user()->can('customer.delete')) {
                $customer->forcedelete();
            } elseif (Auth::user()->can('customer.destroy')) {
                $customer->update(['deleted_by' => Auth::id()]);
                $customer->delete();
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
