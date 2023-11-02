<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'image';
    protected $primaryKey = 'img_id';
    protected $fillable = ['img_id','product_id','image_path'];
}
