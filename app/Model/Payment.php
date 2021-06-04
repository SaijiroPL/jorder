<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table = 'payments';

    public static function get_item_for_sort($key){
        if(self::where('id', '=', $key)->exists()){
            return self::where('id', '=', $key)->first();
        } else if(self::where('name', '=', $key)->exists()) {
            return self::where('name', '=', $key)->first();
        } else {
            return null;
        }
    }
}
