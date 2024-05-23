<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDiscount extends Model
{
    use SoftDeletes;

    public function discount($productId, $qty)
    {
        return $this
            ->where('product_id', $productId)            
            ->where('qty', '<=', $qty)
            ->orderBy('qty', 'desc')
            ->first()->price ?? 0;
    }
}