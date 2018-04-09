<?php

namespace App\Http\Controllers;

use App\Account;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function list(Request $request)
    {
        $user = Auth::user();
        $accounts = Account::where('user_id', $user->id)->with('transactions')->get();
        return response()->json($accounts->toArray());
    }
}
