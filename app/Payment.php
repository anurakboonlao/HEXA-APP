<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function approved_by()
    {
        return $this->belongsTo('App\Member', 'approved_by') ?? $this->belongsTo('App\User', 'approved_by');
    }
}