<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    //
    protected $table = 'dishes';
    public function options()
    {
        return $this->hasManyThrough(Option::class, DishOption::class, 'dish_id', 'id', 'id', 'option_id');
    }
    public function dishoptions()
    {
        return $this->hasMany(DishOption::class, 'dish_id');
    }
    public function group()
    {
        return $this->hasOne(Kitchen::class, 'id', 'group_id');
    }
    public function badge()
    {
        return $this->hasOne(Badge::class, 'id', 'badge_id');
    }
    public function discount()
    {
        return $this->hasOne(Discount::class, 'dish_id', 'id');
    }
    public function getDishNameFromId($dish_id)
    {
        self::select('name_en')->where('id', '=', $dish_id)->get()->toArray();
    }
    public function isBuffet()
    {
        $belongCategories = $this->hasManyThrough(Category::class, DishCategory::class, 'dish_id', 'id', 'id', 'categories_id')->get();
        $result = false;
        foreach($belongCategories as $bc) {
            $cat = $bc;
            if ($bc->parent_id) {
                $cat = $bc->parent;
            }
            if ($cat->is_unlimited == 'on') {
                $result = true;
                break;
            }
        }
        return $result;
    }
    public function categories()
    {
        return $this->hasManyThrough(Category::class, DishCategory::class, 'dish_id', 'id', 'id', 'categories_id');
    }
}
