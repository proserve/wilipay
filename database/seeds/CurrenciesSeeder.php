<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesSeeder extends Seeder
{
    public function run()
    {
        DB::table('currencies')->insert([
            'symbol' => '$',
            'name' => 'US Dollar',
            'symbol_native' => '$',
            'decimal_digits' => 2,
            'rounding' => 0,
            'code' => 'USD',
            'created_at' => Carbon::now()->format('Y-m-d H:i:sO')
        ]);
        DB::table('currencies')->insert([
            'symbol' => '€',
            'name' => 'Euro',
            'symbol_native' => '€',
            'decimal_digits' => 2,
            'rounding' => 0,
            'code' => 'EUR',
            'created_at' => Carbon::now()->format('Y-m-d H:i:sO')
        ]);
        DB::table('currencies')->insert([
            'symbol' => '£',
            'name' => 'British Pound Sterling',
            'symbol_native' => '£',
            'decimal_digits' => 2,
            'rounding' => 0,
            'code' => 'GBP',
            'created_at' => Carbon::now()->format('Y-m-d H:i:sO')
        ]);
    }
}