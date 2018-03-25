<?php

namespace App\Http\Controllers;

use App\Account;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function MongoDB\BSON\toJSON;
use GuzzleHttp\Client;

class TransactionController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    function list(Request $request)
    {
        $user = Auth::user();
        $accounts = $user->accounts;
        $resp = [];
        foreach ($accounts as $account) {
            $resp[$account->id] = $account->transactions;
        }
        return response() . toJSON($resp);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    function betweenUsers(Request $request)
    {
        $validatedData = $request->validate([
            'beneficiary_phone' => 'required_without:beneficiary_email',
            'beneficiary_email' => 'required_without:beneficiary_phone|email',
            'amount' => 'required|numeric',
            'account_id' => 'required',
            'purpose' => '',
        ]);
        $user = Auth::user();
        $phoneOrEmail = array_key_exists('beneficiary_phone', $validatedData) ? 'phone' : 'email';
        $payerAccount = Account::findORFail($validatedData['account_id']);
        $amount = $validatedData['amount'];
        if ($payerAccount->user->id != $user->id) abort(400, 'this account does not belong to that user');
        elseif ($payerAccount->amount < $amount) abort(400, 'Your balance is insufficient');
        else {
            $beneficiary = User::where($phoneOrEmail, Str::lower($validatedData['beneficiary_' . $phoneOrEmail]))
                ->with(['accounts' => function ($query) use ($payerAccount) {
                    $query->where('currency_code', $payerAccount->currency_code);
                }])
                ->firstOrFail();
            $beneficiaryAccount = $beneficiary->accounts[0];
            $payerAccount->amount -= $amount;
            $beneficiaryAccount->amount += $amount;
            DB::beginTransaction();
            try {
                $beneficiaryAccount->save();
                $payerAccount->save();
                Transaction::create([
                    'purpose' => $validatedData['purpose'],
                    'beneficiary_id' => $beneficiary->id,
                    'type' => 'between_users',
                    'amount' => $amount,
                    'account_id' => $validatedData['account_id']
                ]);
                DB::commit();
                return response('', 200);
            } catch (\Exception $e) {
                DB::rollback();
                abort(400, 'Transaction can not be completed please try again');
            }
        }
    }

    public function getCurrenciesRates()
    {
        $httpClient = new Client();
        $data = $httpClient->request('GET', config('app.currency_rate_api_url'));
        $json_decode = json_decode($data->getBody(), true);
        $rates = $json_decode['rates'];
        $rates['USD'] = 1;

        return $rates;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    function convert(Request $request)
    {
        $validatedData = $request->validate([
            'sellAmount' => 'required|numeric',
            'sellAccountId' => 'required',
            'buyAmount' => 'required|numeric',
            'buyAccountId' => 'required',
        ]);
        $user = Auth::user();

        $sellAccount = Account::where('id', $validatedData['sellAccountId'])->where('user_id', $user->id)->firstOrFail();
        $buyAccount = Account::where('id', $validatedData['buyAccountId'])->where('user_id', $user->id)->firstOrFail();
        $sellAmount = $validatedData['sellAmount'];
        $buyAmount = $validatedData['buyAmount'];
        if ($sellAccount->amount < $sellAmount) return abort(400, 'You balance is insufficient');
        $currenciesRates = $this->getCurrenciesRates();
        $buyRate = $currenciesRates[$buyAccount->currency_code];
        $sellRate = $currenciesRates[$sellAccount->currency_code];
        if (round($sellAmount * ($buyRate / $sellRate), 2) != $buyAmount) {
            return abort(400, 'convert data are not correct');
        }
        DB::beginTransaction();
        try {
            $sellAccount->amount -= $sellAmount;
            $buyAccount->amount += $buyAmount;
            $sellAccount->save();
            $buyAccount->save();
            Transaction::create([
                'purpose' => 'Currency Exchange from '. $sellAccount->currency_code. ' to '. $buyAccount->currency_code,
                'beneficiary_id' => $user->id,
                'type' => 'exchange_sell',
                'amount' => $sellAmount,
                'account_id' => $sellAccount->id
            ]);
            Transaction::create([
                'purpose' => 'Currency Exchange from '. $sellAccount->currency_code. ' to '. $buyAccount->currency_code,
                'beneficiary_id' => $user->id,
                'type' => 'exchange_buy',
                'amount' => $buyAmount,
                'account_id' => $buyAccount->id
            ]);
            DB::commit();
            return response('', 200);
        } catch (\Exception $e) {
            DB::rollback();
            abort(400, 'Transaction can not be completed please try again');
        }
    }


    function createTransaction(Request $request, $id)
    {

    }

    function getTransaction(Request $request, $id)
    {

    }
}
