<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    //
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status','active');
    }

    public function description()
    {
        return $this->morphOne('App\Description', 'descriptionable');
    }

    public function image()
    {
        return $this->morphOne('App\Image','imageable');
    }

    public function exchanges()
    {
        return $this->belongsToMany('App\Exchange')->withTimestamps();
    }

    public function visible_exchanges(){
        return $this->belongsToMany('App\Exchange')->whereRaw("FIND_IN_SET('show',status)");
    }

    public function wallets()
    {
        return $this->belongsToMany('App\Wallet')->withTimestamps();
    }

    public function visible_wallets()
    {
        return $this->belongsToMany('App\Wallet')->where('visibility',true);
    }

    public function country()
    {
        return $this->belongsTo('App\Country')->withDefault([
            'name' => 'Other',
            'name_persian' => "نامشخص",
            'code' => 'OT'
        ]);
    }

    public function miningPools()
    {
        return $this->belongsToMany('App\MiningPool')->withTimestamps();
    }

    public function visible_miningPools()
    {
        return $this->belongsToMany('App\MiningPool')->where('visibility',true);
    }

    public function trading_signal()
    {
        return $this->hasOne('App\TradingSignal');
    }
}
