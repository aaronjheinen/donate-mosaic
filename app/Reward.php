<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rewards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['set_id', 'blocks', 'number', 'name', 'description', 'media_id'];

    /**
     * Get all of the posts for the country.
     */
    public function media()
    {
        return $this->belongsTo('App\Media');
    }

    public function set()
    {
        return $this->belongsTo('App\Set');
    }
    
}
