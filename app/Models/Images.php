<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Images extends Model
{
    use HasFactory;

    protected $table = 'images_tbl';
    protected $primaryKey = 'id';
    protected $fillable = ['image'];

    public function saveImage($data)
    {

        Model::insert($data);
        return true;
    }
}
