<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobOrderItems extends Model
{
    public function um()
    {
    	return $this->belongsTo('App\UnitOfMeasurement', 'uom');
    }
}
