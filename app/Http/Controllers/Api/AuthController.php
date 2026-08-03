<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $credentials = [
            'email'=>$request->email,
            'password'=>$request->password,
        ];

        if(!$token = auth('api')->attempt($credentials))
        {
            return response()->json([
                'message'=>'Email ou mot de passe incorrect.'
            ],401);
        }
        

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'message'=>'Déconnexion réussie.'
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(
            auth('api')->refresh()
        );
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'expires_in'=>auth('api')->factory()->getTTL()*60,
            'user'=>auth('api')->user()
        ]);
    }
}