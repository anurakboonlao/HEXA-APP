<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberLogin extends Model
{
    use SoftDeletes;

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function findBy($field, $value)
    {
        return $this->where($field, $value)->first();
    }

    public function zone_member()
    {
        return $this->belongsTo('App\ZoneMember', 'member_id');
    }
}