<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseSquare extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_square';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['purchase_id', 'square_id'];

}
