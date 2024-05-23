<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public function getDataToSelect()
    {
        return array_pluck($this->orderBy('name', 'asc')->get(), 'name', 'id');
    }
    
}
