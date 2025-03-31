<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistroRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegistroRequest $request)
    {
        // Obtener los datos validados
        $data = $request->validated();

        // Crear el usuario
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // Devolver el token y el usuario en formato JSON
        return response()->json([
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ], 201); // 201 es el código HTTP para recurso creado
    }

    public function login(LoginRequest $request)
    {
        // Obtener los datos validados
        $data = $request->validated();

        // Intentar autenticar al usuario
        if (!Auth::attempt($data)) {
            // Si las credenciales son incorrectas, devolver error 422
            return response()->json([
                'errors' => ["Email o password son incorrectos"]
            ], 422);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Devolver el token y el usuario en formato JSON
        return response()->json([
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        // Invalidar el token actual del usuario
        $request->user()->currentAccessToken()->delete();

        // Devolver respuesta indicando que el logout fue exitoso
        return response()->json(['message' => 'Logout successful'], 200);
    }
}
