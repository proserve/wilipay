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
class Account extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
    protected $fillable = [
        'user_id', 'currency_code', 'amount',
    ];

     protected $visible = [
        'amount', 'currency_code', 'id', 'transactions'
    ];

    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
