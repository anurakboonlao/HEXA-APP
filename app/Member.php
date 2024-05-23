<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    public function getDataToSelect($conditions = [])
    {
        return array_pluck($this->where($conditions)->orderBy('name', 'asc')->get(), 'name', 'id');
    }

    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }

    public function in_zone()
    {
        return $this->hasMany('App\ZoneMember')->first() ?? null;
    }

    public function zone_members()
    {
        return $this->hasMany('App\ZoneMember');
    }

    public function logins()
    {
        return $this->hasMany('App\MemberLogin')->orderBy('id', 'desc')->limit(10);
    }

    public function contacts()
    {
        return $this->hasMany('App\DoctorContact');
    }

    public function totalPointRedeemed()
    {
        $members = app()->make('App\Member')->where('customer_id', $this->customer_id)->get();
        $memberIds = $members->pluck('id');

        return app()->make('App\Redemption')->whereIn('member_id', $memberIds)->whereYear('updated_at', date('Y'))->sum('point');
    }

    public function totalPointVoucherCart()
    {
        $members = app()->make('App\Member')->where('customer_id', $this->customer_id)->get();
        $memberIds = $members->pluck('id');

        return app()->make('App\VoucherCart')->whereIn('member_id', $memberIds)->whereYear('updated_at', date('Y'))->sum('point');
    }

    public function voucher_carts()
    {
        return $this->hasMany('App\VoucherCart');
    }
}