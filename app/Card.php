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
class Card extends Model
{
     use SoftDeletes, Uuids;
     public $incrementing = false;

     public function user()
    {
        return $this->belongsTo('App\User');
    }

    protected $fillable = [
        'user_id', 'brand', 'last4', 'exp_year', 'exp_month', 'country', 'card_id', 'token_id',
    ];

    protected $visible = ['brand', 'exp_year', 'exp_month', 'country', 'last4'];

     protected $dates = ['deleted_at'];
}
