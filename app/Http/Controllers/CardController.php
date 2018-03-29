<?php

namespace App\Http\Controllers;

use App\Account;
use App\Card;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CardController extends Controller
{
    public function add(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'brand' => ['required', Rule::in(config('app.card_brands'))],
            'exp_year' => 'required|numeric|min:'.date('Y'),
            'exp_month' => 'required|numeric|between:0,12',
            'country' => 'required|size:2',
            'card_id' => 'required|max:64|unique:cards',
            'token_id' => 'required|max:64',
            'last4' => 'required|size:4',
        ]);
        $validatedData['user_id'] = $user->id;
        return Card::create($validatedData);
    }
}
