<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error',
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Generar un random entre 200 y 500
            $random = rand(200, 500);

            // Crear el token combinando email, timestamp y random, luego encriptar con SHA1
            $tokenData = $user->email . now() . $random;
            $token = sha1($tokenData);

            // Calcular la fecha de expiración (ejemplo: 1 hora desde la creación)
            $expiresAt = Carbon::now()->addHour();
            Token::create([
                'user_id' => $user->id,
                'token' => $token,
                'user' => $credentials['email'],
                'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
            ]);

            return response()->json(['token' => $token, 'expires_at' => $expiresAt->format('Y-m-d H:i:s')], 200);
        }

        return response()->json(['error' => 'Unauthorized', 'credenci' => $credentials], 401);
    }
}
