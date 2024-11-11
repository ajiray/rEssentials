<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_id',
        'user_id',
        'quantity',
        'receipt',
        'receipt_two',
        'down_payment',
        'total_price',
        'reservation_date',
        'status',
        'due_date',
    ];

    // Relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship with the ProductVariant model
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Relationship with the User model
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
}