<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthServices extends BaseController
{
    public function register($request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->successHandler('Create User Success', $user);
    }

    public function login($request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->errorHandler('Invalid Email Or Password!', []);
        }

        $user = Auth::user();
        $user['token'] = $user->createToken('authToken')->plainTextToken;

        return $this->successHandler('Login Success', $user);
    }
}