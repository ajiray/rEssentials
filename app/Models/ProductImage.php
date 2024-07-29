<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = ['product_variant_id', 'path'];

    public function variant()
{
    return $this->belongsTo(ProductVariant::class);
}
}
