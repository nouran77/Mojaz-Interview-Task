<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['title','list_id'];

    public function lists()
    {
        return $this->hasMany('App\ListItem','list_id');
    }
}
