<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WPPOSTMETA extends Model
{
    //

    protected $connection = 'wordpress_db';
    protected $table = 'postmeta';
    protected $primaryKey = 'meta_id';

}
