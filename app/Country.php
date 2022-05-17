<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //

    public function exchanges()
    {
        return $this->hasMany('App\Exchange');
    }
    public function coins()
    {
        return $this->hasMany('App\Coin');
    }
    public function mining_companies()
    {
        return $this->hasMany('App\MiningCompany');
    }

    public function crypto_atms()
    {
        return $this->hasMany('App\CryptoATM');
    }

    public function scopeVisible($query){

        return $query->where('visibility',true);
    }
}
