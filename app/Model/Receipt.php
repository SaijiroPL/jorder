<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    //
    protected $table = 'receipt';
    public static function profile()
    {
        return self::find(1);
    }
}
