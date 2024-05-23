<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}