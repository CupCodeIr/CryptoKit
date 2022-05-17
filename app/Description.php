<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    //
    protected $fillable = ['description'];
    public function descriptionable()
    {
        return $this->morphTo();
    }
}
