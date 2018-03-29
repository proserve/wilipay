<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property int $id
 * @property \Carbon\Carbon $updated_at
 * @property mixed $email
 * @property mixed $profile
 */
class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, Notifiable, Uuids;

    public $incrementing = false;

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function accounts()
    {
        return $this->hasMany('App\Account');
    }

    public function AauthAcessToken()
    {
        return $this->hasMany('\App\OauthAccessToken');
    }

    public function cards()
    {
        return $this->hasMany('\App\Card');
    }

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'country_prefix', 'national_number', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];
    protected $visible = ['email', 'phone', 'national_phone', 'profile', 'accounts'];


    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public static function created($callback)
    {
    }


}
