<?php

namespace App\Http\Controllers;

use App\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function list()
    {
        return Currency::simplePaginate(15);
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
