<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\AccountKit;
class LoginController
{
    public function otpLogin(Request $request)
    {
        $client = new AccountKit();
        $data = $client->data($request->code);
        return response()->json($data);
    }
}