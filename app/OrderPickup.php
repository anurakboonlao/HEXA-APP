<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPickup extends Model
{
    use SoftDeletes;
    
    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function sale()
    {
        return $this->belongsTo('App\Member', 'sale_id');
    }
}