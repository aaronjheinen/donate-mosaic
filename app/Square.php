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
    protected $fillable = ['set_id', 'name', 'email', 'status', 'class'];

     /**
     * Get the post that owns the comment.
     */
    public function purchase()
    {
        return $this->belongsTo('App\Purchase');
    }

    public function set()
    {
        return $this->belongsTo('App\Set');
    }

}
