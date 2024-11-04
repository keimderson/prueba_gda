<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Carbon\Carbon;

class customerController extends Controller
{
    public function createCustomer(Request $request)
    {
        $dataCustomer = $request->only('dni', 'id_reg', 'id_com', 'email', 'name', 'last_name', 'address');

        $dateNow = Carbon::now();

        $customer = Customer::create([
            'dni'       => $dataCustomer['dni'],
            'id_reg'    => $dataCustomer['id_reg'],
            'id_com'    => $dataCustomer['id_com'],
            'email'     => $dataCustomer['email'],
            'name'      => $dataCustomer['name'],
            'last_name' => $dataCustomer['last_name'],
            'address'   => $dataCustomer['address'],
            'date_reg'  => $dateNow
        ]);

        if(!$customer)
        {
            $data = [
                'message' => 'Error al crear el registro',
                'success' => false,
                'status' => 500
            ];
            return response()->json($data, 500);
        }
        $data = [
            'customer' => $customer,
            'success' => true,
            'status' => 200
        ];

        return response()->json($data, 201);
    }
    public function getCustomer($id)
    {

        $customer = Customer::with(['region', 'commune'])
                          ->where('dni', $id)
                          ->where('status', 'A')
                          ->first();
    

        if (!$customer) {
            $customer = Customer::with(['region', 'commune'])
                              ->where('email', $id)
                              ->where('status', 'A')
                              ->first();
        }

        if (!$customer) {
            $data = [
                'message' => 'Registro no existe',
                'success' => false,
                'status' => 404
            ];
            return response()->json($data, 404);
        }
    
        $data = [
            'customer' => [
                'dni' => $customer->dni,
                'email' => $customer->email,
                'name' => $customer->name,
                'last_name' => $customer->last_name,
                'region_description' => $customer->region->description ?? null,
                'commune_description' => $customer->commune->description ?? null,
                'estatus' => $customer->estatus,
            ],
            'success' => true,
            'status' => 200
        ];
    
        return response()->json($data, 200);
    }

    public function deleteCustomer($dni)
{

    $customer = Customer::where('dni', $dni)
                      ->where('status', 'A')
                      ->first();


    if (!$customer) {
        $data = [
            'message' => 'Registro no encontrado o ya eliminado',
            'success' => false,
            'status' => 404
        ];
        return response()->json($data, 404);
    }

    $customer->status = 'trash';
    $customer->save();

    $data = [
        'message' => 'Registro eliminado exitosamente',
        'success' => true,
        'status' => 200
    ];

    return response()->json($data, 200);
}



}