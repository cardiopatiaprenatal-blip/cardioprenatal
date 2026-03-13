<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'crm' => ['required', 'string', 'min:4', 'max:20'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $crm = strtoupper(trim($request->crm));
        if (Auth::attempt(['crm' => $crm, 'password' => $request->password])) {
            $user = Auth::user();
            // Cria um token de API usando o Laravel Sanctum
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }
    }

    public function logout(Request $request)
    {
        // Invalida o token de API atual do usuário (requer autenticação via Sanctum)
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

}
