<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['ProductCode', 'ProductName', 'Image', 'Qty', 'Price', 'CreateDate'];
}
