<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
