<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id', 'price', 'name', 'email', 'comment', 'media_id'];

    /**
     * Get all of the posts for the country.
     */
    public function squares()
    {
        return $this->belongsToMany('App\Square', 'purchase_square');
    }

}
