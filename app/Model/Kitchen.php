<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Kitchen extends Model
{
    //
    protected $table = 'groups';

    public function Dish()
    {
        return $this->hasMany(Dish::class, 'group_id');
    }
}
