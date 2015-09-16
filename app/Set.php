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
    protected $fillable = ['name', 'rows', 'cols', 'price', 'available', 'media_id'];

     
    public function squares()
    {
      return $this->HasMany('App\Square');
    }

    public function rewards()
    {
      return $this->HasMany('App\Reward')->orderBy('blocks');
    }

}
