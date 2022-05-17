<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    protected $casts = [
        'platform' => 'array',
        'wallet_features' => 'array',
        'rating' => 'array',
        'rank' => 'integer',
        'visibility' => 'boolean',
        'has_trading_facilities' => 'boolean'
    ];
    protected $guarded = [];

    public function coins()
    {
        return $this->belongsToMany('App\Coin')->withTimestamps();
    }

    public function description()
    {
        return $this->morphOne('App\Description', 'descriptionable');
    }

    public function image(){
        return $this->morphOne('App\Image','imageable');
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility',true);
    }

    public function scopeWithSecurity($query,$security)
    {
        return $query->where('security', $security);
    }
    public function scopeHasTradingFacility($query,$yes)
    {
        return $query->where('has_trading_facilities', $yes);
    }

    public function scopeWithPlatforms($query,$platforms)
    {
        return $query->where(function ($query) use($platforms){

            foreach ($platforms as $platform){
                $query->orWhere('platform','like',"%$platform%");
            }
        });
    }
    public function scopeWithFeatures($query,$features)
    {
        return $query->where(function ($query) use($features){

            foreach ($features as $feature){
                $query->orWhere('wallet_features','like',"%$feature%");
            }
        });
    }
}
