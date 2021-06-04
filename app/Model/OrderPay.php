<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderPay extends Model
{
    //
    protected $table = 'order_pay';

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
