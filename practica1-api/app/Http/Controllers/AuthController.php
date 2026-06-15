<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'cliente',
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    #[OA\Post(
        path: '/api/v1/login',
        tags: ['Autenticación'],
        summary: 'Iniciar sesión',
        description: 'Permite autenticar un usuario y obtener un token Sanctum.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        format: 'email',
                        example: 'admin@test.com'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        example: 'password123'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login exitoso'
            ),
            new OA\Response(
                response: 401,
                description: 'Credenciales incorrectas'
            ),
            new OA\Response(
                response: 422,
                description: 'Error de validación'
            )
        ]
    )]
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión correcto',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->rol,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'rol' => $user->rol,
            'permisos' => [
                'crear' => $user->can('crear-producto'),
                'editar' => $user->can('editar-producto'),
                'eliminar' => $user->can('eliminar-producto'),
            ],
        ]);
    }
}