<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class checkGetCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        $dataGetCustomer = $request->route('id'); 

        if (!$dataGetCustomer) {
            $data = [
                'message' => 'Error in data validation',
                'errors' => 'null',
                'status' => 400
            ];
            return response()->json($data, 400);
        }


        return $next($request);
    }
}
