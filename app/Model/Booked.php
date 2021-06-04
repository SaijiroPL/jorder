<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Booked extends Model
{
    //
    protected $table = 'bookeds';

    public function bookedtables()
    {
        return $this->hasMany(BookedTable::class, 'book_id');
    }
}
