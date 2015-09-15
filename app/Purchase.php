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
    protected $fillable = ['customer_id', 'price', 'name', 'email', 'comment', 'media_id', 'color'];

    /**
     * Get all of the squares for the purchase.
     */
    public function squares()
    {
        return $this->belongsToMany('App\Square', 'purchase_square');
    }
    /**
     * Get all of the rewards for the purchase.
     */
    public function rewards()
    {
        return $this->belongsToMany('App\Reward', 'purchase_reward');
    }
    /**
     * Get the media associated with the purchase.
     */
    public function media()
    {
        return $this->belongsTo('App\Media');
    }
}
