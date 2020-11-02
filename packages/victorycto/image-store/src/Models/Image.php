<?php

namespace victorycto\ImageStore\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = [];

    public function getTable()
    {
        return config('imagestore.table_name');
    }
}
