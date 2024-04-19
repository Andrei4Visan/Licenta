<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password'=>'required',
            'remember'=>'boolean'
        ]);
        $remember = $credentials['remeber'] ?? false;
        unset($credentials['remeber']);
        if(!Auth::attempt($credentials,$remember)){
            return response([
                'message'=>'Adresa de email sau parola este greșită!'
            ],422); // 422 = s-a inteles cererea, dar nu se poate indeplini
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if(!$user->is_admin){
            Auth::logout();
            return response([
                'message' => 'Nu ai permisiunea să te autentifici ca admin!'
            ], 403); //403 = interzis
        }
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user'=>$user,
            'token'=>$token
        ]);
    }
    public function logout(){
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response('',204); //204 = indică faptul că serverul a procesat cu succes cererea clientului și că nu există nicio conținută de returnat

    }
}
