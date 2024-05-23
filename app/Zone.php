<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use SoftDeletes;

    public function getDataToSelect()
    {
        return array_pluck($this->orderBy('name', 'asc')->get(), 'name', 'id');
    }

    public function getDataZoneNotInZoneMember($condition = [])
    {
        return array_pluck($this->orderBy('name', 'asc')->where($condition)->get(), 'name', 'id');
    }

    public function members()
    {
        return $this->hasMany('App\ZoneMember');
    }
}
