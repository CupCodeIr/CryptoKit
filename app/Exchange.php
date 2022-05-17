<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    //
    protected $guarded = [];

    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }

    public function description()
    {
        return $this->morphOne('App\Description', 'descriptionable');
    }

    public function scopeVisible($query)
    {
        return $query->whereRaw("FIND_IN_SET('show',status)");
    }

    public function scopeActive($query)
    {
        return $query->whereRaw("FIND_IN_SET('active',status)");
    }

    public function scopeOfType($query, $shouldBeCentralized)
    {
        return $query->where('centralized', $shouldBeCentralized);
    }

    public function scopeOfCountries($query, $countries_ids)
    {

        return $query->whereIn('country_id', $countries_ids);
    }

    public function country()
    {
        return $this->belongsTo('App\Country')->withDefault([
            'name' => 'Other',
            'name_persian' => "نامشخص",
            'code' => 'OT'
        ]);
    }

    public function coins()
    {
        return $this->belongsToMany('App\Coin')->withTimestamps();
    }


    public function active_coins_ordered()
    {
        return $this->belongsToMany('App\Coin')->where(['status' => "active"])->orderByDesc("market_cap");
    }
}
