<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VoucherCart extends Model
{
    use SoftDeletes;

    public function voucher()
    {
        return $this->belongsTo('App\Voucher', 'voucher_id');
    }

    public function voucher_cart()
    {
        return $this->belongsTo('App\Voucher', 'voucher_id');
    }
}
