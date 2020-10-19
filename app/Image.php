<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 * @package App
 *
 * @property $key_name;
 * @property $size;
 * @property $cdn_url;
 */
class Image extends Model
{
    protected $fillable = [
        'key_name',
        'size',
        'cdn_url'
    ];
}
