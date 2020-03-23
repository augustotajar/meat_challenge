<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user that owns the cart.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The products that belong to the role.
     */
    public function products()
    {
        return $this->belongsToMany('App\Product')->withPivot('quantity');
    }

    /**
     * The payments that belong to the role.
     */
    public function payments()
    {
        return $this->hasMany('App\Payment');
    }
}
