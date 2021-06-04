<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDish extends Model
{
    use SoftDeletes;
    //
    protected $table = 'order_dish_match';

    public function Order_Option()
    {
        return $this->hasMany(OrderOption::class, 'order_dish_id');
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class, 'dish_id');
    }
}
