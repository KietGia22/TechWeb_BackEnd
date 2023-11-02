<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    protected $primaryKey = 'category_id';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'category_id',
        'category_name'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function productShip():BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_category', 'category_id', 'product_id');
    }
}
