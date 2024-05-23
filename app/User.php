<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getGroup($field)
    {
        return $this->select($field)
            ->groupBy($field)
            ->orderBy($field ,'asc')
            ->get();
    }

    public function orders()
    {
        return $this->hasMany('App\Order')->orderBy('created_at', 'desc');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment')->orderBy('created_at', 'desc');
    }
}
