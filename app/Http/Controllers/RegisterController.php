<?php

namespace App\Http\Controllers;

use App\Mail\TestEmail;
use App\Providers\AccountKit;
use App\Account;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\Customer;


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
        if(isset($data['email']) ){
            $data['email'] = strtolower($data['email']);
        }
        $this->validator($data)->validate();

        DB::beginTransaction();
        $customer = null;
        try {
            $user = User::create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $token = $user->createToken(config('app.grantName'))->accessToken;

            foreach (array_keys(Config("currencies")) as $currency) {
                Account::create(['amount' => 0, 'user_id' => $user->id, 'currency_code' => $currency]);
            }
            try {
                $customer = Customer::create(array(
                    "description" => "Customer for " . $user->email,
                    "email" => $user->email,
                    "metadata" => ["id" => $user->id]
                ));
            } catch (\Stripe\Error\Base $e) {
                $e.message();
            }
            $user->stripe_customer_id = $customer->id;
            $user->save();
            DB::commit();
            return response(['token' => $token], 201);
        } catch (\Exception $e) {
            DB::rollback();
            if($customer && $customer->id) $customer->delete();
            abort(400, $e->getMessage() || 'Error while creating your account, please try again');
        }

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
            try {
                $user->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 7) {
                    abort(400, 'This phone number has been already taken');
                }
                abort(400, 'Error occur while adding this phone number to your account');
            }
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