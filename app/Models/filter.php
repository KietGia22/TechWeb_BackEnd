<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class filter extends Model
{
    public $sortBy;
    public $isDescending = false;
    public $minPrice;
    public $searchKey;
    public $maxPrice;

    public $categoryId;
    public $supplierId;
    public $pageNumber = 1;
    public $pageSize = 10;
}
