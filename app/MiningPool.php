<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MiningPool extends Model
{
    //
    protected $casts = [
        'source_id' => 'string',
        'can_merge_mining' => 'boolean',
        'tx_fee_shared_with_miner' => 'boolean',
        'visibility' => 'boolean',
        'pool_features' => 'array',
        'fee_expanded' => 'array',
        'minimum_payout' => 'array',
        'server_locations' => 'array',
        'merged_mining_coins' => 'array',
        'payment_type' => 'array',
        'rating' => 'array'
    ];

    protected $guarded = [];

    public function image()
    {
        return $this->morphOne('App\Image','imageable');
    }

    public function description()
    {
        return $this->morphOne('App\Description', 'descriptionable');
    }

    public function coins()
    {
        return $this->belongsToMany('App\Coin')->withTimestamps();
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility',true);
    }

    public function active_coins()
    {
        return $this->belongsToMany('App\Coin')->where('status','active');
    }

    public function scopeCanMergeMining($query,$can)
    {
        return $query->where('can_merge_mining',$can);
    }
    public function scopeTxFeeSharing($query,$yes)
    {
        return $query->where('tx_fee_shared_with_miner',$yes);
    }
    public function scopeBetweenAverageFee($query,$min,$max)
    {
        return $query->whereBetween('average_fee',[$min,$max]);
    }
    public function scopeWithFeatures($query,$features)
    {
        return $query->where(function ($query) use($features){

            foreach ($features as $feature){
                $query->orWhere('pool_features','like',"%$feature%");
            }
        });
    }

    public function scopeWithPaymentType($query,$payment_types)
    {
        return $query->where(function ($query) use($payment_types){

            foreach ($payment_types as $type){
                $query->orWhere('payment_type','like',"%$type%");
            }
        });
    }

}
