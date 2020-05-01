<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'location', 'name','created_at', 'updated_at', 'parent_id'
    ];
    //
    public function thumbnails()
    {
        return $this->hasMany('App\Image');
    }

    public function parent()
    {
        return $this->belongsTo('App\Image');
    }
}
