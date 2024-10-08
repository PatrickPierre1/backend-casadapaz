<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Credenciais inválidas'
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'message' => 'Login realizado com sucesso',
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Erro ao realizar o login',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'message' => 'Usuário registrado com sucesso',
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Erro ao registrar o usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $user->name = $request->name ?? $user->name;
            $user->email = $request->email ?? $user->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json([
                'message' => 'Usuário atualizado com sucesso',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar o usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'message' => 'Usuário removido com sucesso',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao remover o usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
