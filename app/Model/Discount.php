<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    //
    protected $table = 'discounts';

    public function dish()
    {
        return $this->hasOne(Dish::class, 'id', 'dish_id');
    }
}