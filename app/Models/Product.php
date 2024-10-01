<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    // Only these fields can be mass-assigned by the user
    protected $fillable = [
        'product_name',
        "quantity",
        "price",
        "description"
    ];
}
