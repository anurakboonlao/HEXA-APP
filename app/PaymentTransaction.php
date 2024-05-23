<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransaction extends Model
{
  
  protected $fillable = [
    'payment_type',
    'is_success',
    'is_transfer_confirmed',
  ];

  use SoftDeletes;

    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    public function paymentTransactionFiles()
    {
      return $this->hasMany(PaymentTransactionFile::class);
    }
}
