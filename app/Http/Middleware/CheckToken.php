<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Token;
use Carbon\Carbon;

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        $tokenValue = $request->header('Authorization');

        if (!$tokenValue) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        // Buscar el token en la base de datos
        $token = Token::where('token', $tokenValue)->first();

        // Verificar si el token existe y no ha expirado
        if (!$token || Carbon::now()->greaterThan($token->expires_at)) {
            return response()->json(['error' => 'Token is invalid or expired', 
            'success' => false], 401);
        }

        return $next($request);
    }
}
