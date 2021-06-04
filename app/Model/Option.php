<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    //
    protected $table = 'options';
    public function items()
    {
        return $this->hasMany(Item::class, 'option_id');
    }
}
