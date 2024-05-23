<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherOption extends Model
{
    use SoftDeletes;

    public function voucher()
    {
        return $this->belongsTo('App\Voucher');
    }
}