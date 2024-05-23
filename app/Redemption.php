<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Redemption extends Model
{
    use SoftDeletes;
    
    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function voucher()
    {
        return $this->belongsTo('App\Voucher');
    }
}