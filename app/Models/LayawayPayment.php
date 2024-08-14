<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayawayPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_date',
        'amount',
        'status',
        'receipt',
        'is_initial_payment',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
