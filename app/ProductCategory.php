<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;

    public function getListToSelect()
    {
        return array_pluck($this->orderBy('name', 'asc')->get(), 'name', 'id');
    }
}
