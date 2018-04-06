<?php

namespace App\Http\Controllers;

use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);
        $username = strtolower($data['username']);
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user = User::where($field, $username)->firstOrFail();

        if (Hash::check($data['password'], $user->password)) {
            $usersJson = $user->load('profile')->load('accounts.transactions')->load('cards')->toArray();
            $accessToken = $user->createToken('wilipay Personal Access Client')->accessToken;
            return response()->json(['user' => $usersJson, 'token' => $accessToken]);
        }
        abort(401);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }
    }
}
