<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'price',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'variant_id');
    }

    // Method to add variant to cart
    public function addToCart($userId, $quantity)
    {
        // Check if the variant has enough quantity
        if ($this->quantity >= $quantity) {
            // Use a transaction to ensure data consistency
            DB::transaction(function () use ($userId, $quantity) {
                // Create a new CartItem
                $cartItem = new CartItem();
                $cartItem->user_id = $userId;
                $cartItem->product_id = $this->product_id;
                $cartItem->variant_id = $this->id;
                $cartItem->quantity = $quantity;
                $cartItem->save();

                // Decrease the variant quantity
                $this->quantity -= $quantity;
                $this->save();
            });

            return true; // Variant added to cart successfully
        } else {
            return false; // Not enough quantity available
        }
    }

    // Method to remove variant from other customers' carts if the stock is zero
    public function removeFromOtherCartsIfOutOfStock()
    {
        // Check if the quantity is 0
        if ($this->quantity == 0) {
            // Remove this product variant from all carts except the one who just bought it
            CartItem::where('variant_id', $this->id)
                    ->where('quantity', '>', 0)
                    ->delete();
        }
    }
}
