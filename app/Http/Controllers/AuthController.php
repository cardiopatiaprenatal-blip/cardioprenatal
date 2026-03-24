<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'crm' => ['required', 'string', 'min:4', 'max:20'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $crm = strtoupper(trim($request->crm));
        $auth = Auth::attempt([
            'crm' => $crm,
            'password' => $request->password
        ]);

        if ($auth) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Login successful']);
        }

        return response()->json([
            'message' => 'CRM ou senha inválidos'
        ], 401);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function registerIndex()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome'          => ['required', 'string', 'max:255'],
            'crm'           => ['required', 'string', 'min:4', 'max:20', 'unique:usuarios,crm'],
            'telefone'      => ['required', 'string', 'max:20'],
            'password'      => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = \App\Models\User::create([
            'nome'          => $request->nome,
            'crm'           => strtoupper(trim($request->crm)),
            'telefone'      => $request->telefone,

            'password'      => bcrypt($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['message' => 'Cadastro realizado com sucesso!']);
    }
}