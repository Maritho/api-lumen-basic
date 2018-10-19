<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (count($user) === 0) {
            return response()->json([
                'status' => false,
                'message' => 'Email Tidak Terdaftar',
                'data' => null
            ], 404);
        }

        if (Hash::check($request->password, $user->password)) {
            $token = base64_encode(str_random(40));
            $user->update(['api_token' => $token]);

            return response()->json([
                'status' => true,
                'message' => 'Login Berhasil',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal',
                'data' => null
            ], 400);
        }
        
    }

    /**
     * Create a new controller instance.
     * @param $request
     * @return void
     */
    public function register(Request $request)
    {
        $register = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if ($register) {
            return response()->json([
                'status' => true,
                'message' => 'Pendaftaran Berhasil',
                'data' => $register
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Pendaftaran Gagal',
                'data' => null
            ], 400);
        }
    }
}
