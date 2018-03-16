<?php

namespace App\Http\Controllers;

use App\Mail\TestEmail;
use App\Providers\AccountKit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $this->validator($data)->validate();
        $user = User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        $token = $user->createToken('wilipay Personal Access Client')->accessToken;
        return response(['token' => $token], 201);
    }

    public function editPhone(Request $request)
    {
        $user = Auth::user();
        Validator::make($request->all(), [
            'code' => 'required|string',
        ]);
        $code = $request->all()['code'];
        $client = new AccountKit();
        try {
            $data = $client->data($code);
            $user->phone = $data->phone->number;
            $user->national_phone = $data->phone->national_number;
            $user->country_prefix = $data->phone->country_prefix;
            $user->fb_account_kit_id = $data->id;
            $user->save();
            Mail::to($user)->send(new TestEmail([
                "subject" => "You have add a valid phone number to your account",
                "message" => "You have successfully add a valid phone number (" . $user->phone . ") to your account"
            ]));
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            App::abort($e->getCode(), 'Error while adding this phone number, please try again with another phone');
        }
        return response('', 201);
    }

}