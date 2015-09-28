<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'rows', 'cols', 'price', 'available', 'media_id', 'tracking_id'];

     
    public function squares()
    {
      return $this->HasMany('App\Square');
    }

    public function purchases()
    {
      return $this->HasMany('App\Purchase');
    }

    public function content()
    {
      return $this->HasOne('App\SetContent');
    }

    public function rewards()
    {
      return $this->HasMany('App\Reward')->orderBy('blocks');
    }

    /**
     * Get all of the default images for the set.
     */
    public function media()
    {
        return $this->belongsTo('App\Media');
    }


}
