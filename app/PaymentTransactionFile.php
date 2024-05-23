<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentTransactionFile extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'payment_transaction_id',
        'file_path'
      ];

      public function paymentTransaction()
      {
        return $this->belongsTo(PaymentTransaction::class);
      }

}
