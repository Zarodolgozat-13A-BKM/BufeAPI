<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;


class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = ['samaccountname' => $request->username, 'password' => $request->password];
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Érvénytelen bejelentkezési adatok'], 401);
        }
        $user = Auth::user();
        if ($credentials['samaccountname'] == 'teszt') {
            $user->assignRole('admin');
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sikeresen kijelentkeztél!']);
    }

    public function me(Request $request)
    {
        return response()->json(["full_name" => $request->user()->full_name, "email" => $request->user()->email, "role" => $request->user()->role]);
    }

    public function details(Request $request)
    {
        return response()->json(UserResource::make($request->user()));
    }

    public function isTokenStillValid(Request $request)
    {
        return response()->json(['valid' => $request->user() !== null]);
    }

    public function getReverbAppSecretAndKey(Request $request)
    {
        return response()->json([
            'key' => config('services.reverb.key'),
            'secret' => config('services.reverb.secret')
        ]);
    }
}
