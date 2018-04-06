<?php

namespace App\Http\Controllers;

use App\Account;
use App\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Stripe\Customer;

class CardController extends Controller
{
    public function add(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'brand' => ['required', Rule::in(config('app.card_brands'))],
            'exp_year' => 'required|numeric|min:' . date('Y'),
            'exp_month' => 'required|numeric|between:0,12',
            'country' => 'required|size:2',
            'token_id' => 'required|max:64',
            'last4' => 'required|size:4',
        ]);
        DB::beginTransaction();
        $source = null;
        try {
            $customer = Customer::retrieve($user->stripe_customer_id);
            $source = $customer->sources->create(array("source" => $validatedData['token_id']));
            $validatedData['user_id'] = $user->id;
            $validatedData['source_id'] = $source->id;
            $card = Card::create($validatedData);
            DB::commit();
            return $card;
        } catch (\Exception $e) {
            DB::rollback();
            if ($source && $source->id) $source->delete();
            abort(400, $e->getMessage() || 'Error while creating your account, please try again');
        }
    }

    public function list(Request $request)
    {
        return Auth::user()->cards;
    }
}
