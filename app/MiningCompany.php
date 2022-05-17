<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MiningCompany extends Model
{
    //
    protected $casts = [
        'visibility' => 'boolean',
        'rating' => 'array',
    ];

    protected $guarded = [];

    public function scopeVisible($query)
    {
        return $query->where('visibility',true);
    }

    public function description()
    {
        return $this->morphOne('App\Description', 'descriptionable');
    }

    public function country()
    {
        return $this->belongsTo('App\Country')->withDefault([
            'name' => 'Other',
            'name_persian' => "سایر",
            'code' => 'OT'
        ]);
    }

    public function image()
    {
        return $this->morphOne('App\Image','imageable');
    }

}
