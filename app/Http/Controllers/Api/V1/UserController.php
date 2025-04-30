<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(LoginUserRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        $passwordCheck = Hash::check($data['password'], $user->password);
//        if ($user === null || $passwordCheck === false) {
        if (!$user || !$passwordCheck) {
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Credenciais Inválidas',
            ], 401);
        }
        
        $token = $user->createToken('token-web')->plainTextToken;
        
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Usuário autenticado com sucesso.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }
}
