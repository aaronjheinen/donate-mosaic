<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseReward extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_reward';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['purchase_id', 'reward_id'];

}
