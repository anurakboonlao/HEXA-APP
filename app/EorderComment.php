<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EorderComment extends Model
{
    use SoftDeletes;

    public function member()
    {
        return $this->belongsTo('App\Member');
    }
}