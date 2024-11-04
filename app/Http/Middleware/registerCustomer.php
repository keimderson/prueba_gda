<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;

class registerCustomer
{

    public function handle(Request $request, Closure $next)
    {
        $dataCustomer = $request->only('dni', 'id_reg', 'id_com', 'email', 'name', 'last_name', 'address');

        $validator = Validator::make($dataCustomer, [
            'dni'       => 'required|max:12|unique:Customers',
            'id_reg'    => 'required|digits_between:1,5|exists:regions,id_reg',
            'id_com'    => 'required|digits_between:1,5|exists:communes',
            'email'     => 'required|email|unique:customers',
            'address'   => 'present',
            'name'      => 'required|max:60',
            'last_name' => 'required|max:60'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error in data validation',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        return $next($request);
    }
}
