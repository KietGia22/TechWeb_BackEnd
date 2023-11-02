<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $primaryKey = 'supplier_id';

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'supplier_id',
        'supplier_name'
    ];

    public function products():HasMany
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }


}