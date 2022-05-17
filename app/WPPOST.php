<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WPPOST extends Model
{
    //
    protected $connection = 'wordpress_db';
    protected $table = 'posts';
    protected $primaryKey = 'ID';

    // adding a global scoope in yoour post model
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('post_type', function (Builder $builder) {
            return $builder->where([
                ['post_type','post'],
                ['post_status','publish'],
            ]);

        });
    }


    public function thumbnail_id()
    {
        return $this->hasOne('App\WPPOSTMETA','post_id','ID')->where('meta_key','_thumbnail_id')->withDefault();
    }


    public function thumbnail($post_id){

        $post_thumb = WPPOSTMETA::where([['post_id',$post_id],['meta_key','_wp_attachment_metadata']])->first();

        return ($post_thumb !== null ) ? unserialize($post_thumb->meta_value) : null;
    }

}
