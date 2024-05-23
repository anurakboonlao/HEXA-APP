<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoneMember extends Model
{
    use SoftDeletes;

    public function getListToSelect()
    {
        return array_pluck($this->orderBy('name', 'asc')->get(), 'name', 'id');
    }

    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function logins()
    {
        return $this->hasMany('App\MemberLogin', 'member_id')->orderBy('id', 'desc')->limit(10);
    }
}
