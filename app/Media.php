<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{

   /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'media';

    protected $fillable = array(
        'type',
        'path',
        'url'
        );

    public function purchase()
    {
        return $this->BelongsTo('App\Purchase');
    }
    public function group()
    {
        return $this->BelongsTo('App\Group');
    }
    public function set()
    {
        return $this->BelongsTo('App\Set');
    }
}
