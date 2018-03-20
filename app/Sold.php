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
class Sold extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function transactions()
    {
        return $this->belongsTo('App\Transaction');
    }
    protected $fillable = [
        'user_id', 'currency_id', 'amount',
    ];

     protected $visible = [
        'amount', 'currency',
    ];

    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $dates = ['deleted_at'];
}
