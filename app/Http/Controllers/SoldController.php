<?php

namespace App\Http\Controllers;

use App\Currency;
use App\Sold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoldController extends Controller
{
    public function list(Request $request)
    {
        $user = Auth::user();
        $solds = Sold::with('currency')->where('user_id', $user->id)->get();
        return response()->json($solds->toArray());
    }


    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'symbol' => 'string',
            'symbol_native' => 'string',
            'decimal_digits' => 'integer',
            'flag_url' => 'string|url'
        ]);
        Currency::create($validatedData);
    }


    public function show($id)
    {
        return response()->json(Currency::findOrFail($id)->toArray());
    }

    public function edit(Request $request, $id)
    {
        $validatedData = $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'symbol' => 'string',
            'symbol_native' => 'string',
            'decimal_digits' => 'integer',
            'flag_url' => 'string|url'
        ]);
        Currency::findOrFail($id)->fill($validatedData);
        return response('', 200);
    }

    public function destroy($id)
    {
        Currency::findOrFail($id)->delete();
        return response('', 200);
    }
}
