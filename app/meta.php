<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class meta extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'value' => 'array'
    ];
}
