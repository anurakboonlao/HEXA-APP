<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    public function options()
    {
        return $this->hasMany('App\VoucherOption')->orderBy('redeem_point', 'asc');
    }
}
