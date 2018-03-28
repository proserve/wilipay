<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Transaction extends Model
{
    use SoftDeletes, Uuids;

    public $incrementing = false;

    protected $fillable = [
        'purpose', 'beneficiary_id', 'type', 'amount', 'account_id'
    ];

    public function beneficiary()
    {
        return $this->belongsTo('App\User', 'beneficiary_id');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }



    protected $visible = [
        'purpose', 'beneficiary_id', 'type', 'amount', 'account_id', 'created_at', 'id'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
}
