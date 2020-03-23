<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    /**
     * The carts that belong to the role.
     */
    public function cart()
    {
        return $this->belongsTo('App\Cart');
    }

}
