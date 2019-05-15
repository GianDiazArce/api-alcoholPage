<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = "orders";

    public function products()
    {
        return $this->hasMany('App\Product','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
