<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MiningEquipment extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'rating' => 'array',
        'visibility' => 'boolean',
    ];

    public function image()
    {
        return $this->morphOne('App\Image','imageable');
    }

    public function description()
    {
        return $this->morphOne('App\Description', 'descriptionable');
    }

    public function company(){
        return $this->belongsTo('App\MiningCompany');
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility',true)->orderByDesc('rank');
    }

    public function scopeBetweenPrice($query,$min,$max)
    {
        return $query->whereBetween('cost',[$min,$max]);
    }
    public function scopeBetweenHash($query,$min,$max)
    {
        return $query->whereBetween('hashes_per_second',[$min, $max]);
    }
    public function scopeBetweenPowerConsumption($query,$min,$max)
    {
        return $query->whereBetween('power_consumption',[$min, $max]);
    }

    public function scopeOfAlgorithm($query,$algorithms)
    {
        return $query->whereIn('algorithm',$algorithms);
    }

    public function scopeOfType($query,$types)
    {
        return $query->whereIn('equipment_type',$types);
    }


}
