<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    protected $table = 'lists';

    protected $fillable = ['title'];

    public function items()
    {
        return $this->hasMany('App\Item','list_id');
    }

}
