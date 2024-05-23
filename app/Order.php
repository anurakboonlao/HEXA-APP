<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    public function products()
    {
        return $this->hasMany('App\OrderProduct');
    }

    public function member()
    {
        return $this->belongsTo('App\Member');
    }
}