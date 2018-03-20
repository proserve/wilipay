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
    use SoftDeletes, HasApiTokens, Notifiable;

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function solds()
    {
        return $this->hasMany('App\Sold');
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
    protected $visible = ['email', 'phone', 'national_phone', 'profile'];


    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public static function created($callback)
    {
    }


}
