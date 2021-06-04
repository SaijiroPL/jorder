<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = 'orders';

    public function ordertables()
    {
        return $this->hasMany(OrderTable::class, 'order_id');
    }

    public function orderdishes()
    {
        return $this->hasMany(OrderDish::class, 'order_id');
    }
}
