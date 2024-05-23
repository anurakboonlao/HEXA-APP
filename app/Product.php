<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    public function getDataToSelect()
    {
        return array_pluck($this->orderBy('name', 'asc')->get(), 'name', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\ProductCategory');
    }

    public function discounts()
    {
        return $this->hasMany('App\ProductDiscount')->orderBy('qty', 'desc');
    }

    public function discount($qty)
    {
        return $this->hasMany('App\ProductDiscount')
            //->where('qty', '<=', $qty)
            ->orderBy('qty', 'desc')
            ->first();
    }

    public function memberFavorite($memberId)
    {
        return $this->hasMany('App\Favorite')->where('member_id', $memberId)->first() ?? [];
    }
}
