<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobOrder extends Model
{
    public function user()
    {
    	return $this->belongsTo('App\User');
    }


    public function items()
    {
    	return $this->hasMany('App\JobOrderItems', 'jo_id');
    }


    public function cancelledBy()
    {
    	return $this->belongsTo('App\User', 'cancelled_by');
    }

    public function manager()
    {
        return $this->belongsTo('App\User', 'manager_id');
    }

}
