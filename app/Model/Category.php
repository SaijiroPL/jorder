<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'categories';

    public function subs()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderby('order');
    }

    public function dishes(){
        return $this->hasManyThrough(Dish::class, DishCategory::class, 'categories_id', 'id', 'id', 'dish_id')->where('active',1)->orderby('order');
    }

    public function alldishes() {
        return $this->hasManyThrough(Dish::class, DishCategory::class, 'categories_id', 'id', 'id', 'dish_id');
    }

    public function eat_dishes($eat_in){
        return $this->hasManyThrough(Dish::class, DishCategory::class, 'categories_id', 'id', 'id', 'dish_id')->where('active',1)->where($eat_in,1)->orderby('order')->get();
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public static function get_mains()
    {
        return self::where('parent_id', '=', null)->orderby('order')->get();
    }

}
