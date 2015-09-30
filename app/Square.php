<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Square extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'squares';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['set_id', 'number', 'status', 'class'];

    /**
     * Get all of the posts for the country.
     */
    public function purchase()
    {
        return $this->belongsToMany('App\Purchase', 'purchase_square');
    }

    public function set()
    {
        return $this->belongsTo('App\Set');
    }

}
