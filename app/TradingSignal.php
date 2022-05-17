<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TradingSignal extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'inOutVar' => 'array',
        'largetxsVar' => 'array',
        'addressesNetGrowth' => 'array',
        'concentrationVar' => 'array',
        'time' => 'datetime',
    ];

    public function coin()
    {
        return $this->belongsTo('App\Coin');
    }
}
