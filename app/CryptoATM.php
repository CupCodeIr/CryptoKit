<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CryptoATM extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'create_on_date' => 'datetime',
        'update_on_date' => 'datetime',
        'visibility' => 'boolean'
    ];

    public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
