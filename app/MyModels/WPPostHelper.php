<?php


namespace App\MyModels;


use App\WPPOSTTERM;
use App\WPPOST;

class WPPostHelper
{
    public static function getPosts($num = 3,$category_id = null)
    {
        $posts = WPPOST::with('thumbnail_id')->orderByDesc('post_date_gmt');

        if($category_id !== null){
            $post_ids = WPPOSTTERM::where('term_taxonomy_id',$category_id)->pluck('object_id');
            $posts = $posts->whereIn('ID',$post_ids);
        }
        $posts = $posts->take($num)->get();
        foreach ($posts as $post){

            $post_image = $post->thumbnail($post->thumbnail_id->meta_value);
            if($post_image){
                $image_size = config('wordpress.image_size');
                $file_url_prefix = explode('/',$post_image['file']);
                $file_url_prefix[2] = $post_image['sizes'][$image_size]['file'];
                $post->image = [
                    'url' => config('wordpress.uploads_url','') . implode('/',$file_url_prefix),
                    'width'  => $post_image['sizes'][$image_size]['width'],
                    'height' => $post_image['sizes'][$image_size]['height']
                ];
            }
        }
        return $posts;
    }

}
