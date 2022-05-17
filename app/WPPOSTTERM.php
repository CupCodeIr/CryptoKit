<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WPPOSTTERM extends Model
{
    //
    protected $connection = 'wordpress_db';
    protected $table = 'term_relationships';
    protected $primaryKey = null;

}
