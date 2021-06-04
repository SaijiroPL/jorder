<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    //
    protected $table = 'tables';

    public function order()
    {
        return $this->hasManyThrough(Order::class, OrderTable::class, 'table_id', 'id', 'id', 'order_id');
    }

    public function orderTable()
    {
        return $this->hasMany(OrderTable::class, 'table_id');
    }

    public function book()
    {
        $current_date = date('Y-m-d');
        return $this->hasManyThrough(Booked::class, BookedTable::class, 'table_id', 'id', 'id', 'book_id')
            // ->where('timer_flag','0')
            ->where('time', '<=',$current_date . " 23:59:59" )
            ->where('time', '>=',$current_date . " 00:00:00" )
            ->orderby('time');
    }

    public function bookTable()
    {
        return $this->hasMany(OrderTable::class, 'table_id');
    }
}
