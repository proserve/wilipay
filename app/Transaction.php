<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property \Carbon\Carbon $deleted_at
 */
class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type', 'sold_id', 'amount', 'type', 'purpose', 'beneficiary', 'payer'
    ];

    protected $visible = [
        'type','sold', 'amount', 'type', 'purpose', 'beneficiary', 'payer'
    ];

    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $dates = ['deleted_at'];
}
