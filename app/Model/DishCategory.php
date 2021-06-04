<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Dish;

class DishCategory extends Model
{
    //
    protected $table = 'dish_category_match';

    public static function getCategoryFromDish($dish_id)
    {
        return self::select('categories_id')->where('dish_id', '=', $dish_id)->get()->toArray();
    }

    public static function getDishFromCategory($category_id)
    {
        return self::select('id')->leftjoin('dishes', 'dish_category_match.dish_id', '=', 'dishes.id')->where('categories_id', '=', $category_id)->get();
    }
}
