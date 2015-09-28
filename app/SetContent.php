<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SetContent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'set_content';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['set_id','header','footer','disclaimer'];

    /**
     * Get the media associated with the purchase.
     */
    public function set()
    {
        return $this->belongsTo('App\Set');
    }

}
