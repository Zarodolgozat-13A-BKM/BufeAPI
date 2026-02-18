<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user'
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = $request->user();
        if (!Auth::attempt(['email' => $user->email, 'password' => $request->current_password])) {
            return response()->json(['message' => 'Nem ez a jelenlegi jelszavad!'], 401);
        }
        $user->password = bcrypt($request->new_password);
        $user->save();
        return response()->json(['message' => 'Jelszó sikeresen megváltoztatva!']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = ['email' => $request->username, 'password' => $request->password];

        if (Auth::validate($credentials)) {
            $user = Auth::getLastAttempted();
            return response()->json([
                'access_token' => $user->createToken('auth_token')->plainTextToken,
                'token_type' => 'Bearer',
            ]);
        }
        // if (!$ldapUser || !$ldapUser->authenticate($password)) {
        //     return response()->json(['message' => 'Érvénytelen bejelentkezési adatok'], 401);
        // }
        // if (!Auth::attempt($credentials)) {
        //     return response()->json(['message' => 'Érvénytelen bejelentkezési adatok'], 401);
        // }
        // error_log($ldapUser);
        // $user = User::firstOrCreate(
        //     ['email' => $ldapUser->getEmail()],
        //     ['name' => $ldapUser->getCommonName(), 'password' => bcrypt($password), 'role' => 'user']
        // );
        // $token = $user->createToken('auth_token')->plainTextToken;
        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        // ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sikeresen kijelentkeztél!']);
    }

    public function forgotPassword(Request $request)
    {
        // Email küldés kell majd
    }
}
