<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property \Carbon\Carbon $created_at
 * @property int $id
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class Currency extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'name', 'symbol', 'symbol_native', 'decimal_digits', 'flag_url',
    ];

    protected $visible = [
        'code', 'name', 'symbol', 'symbol_native', 'decimal_digits', 'flag_url',
    ];

    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $dates = ['deleted_at'];
}
