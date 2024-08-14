<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_date',
        'shipping_address',
        'total_amount',
        'payment_method', // Added
        'payment_status',
        'shipping_method',
        'tracking_number',
        'shipping_status',
        'shipping_procedure',
        'receipt',
        'num_orders',
        'layaway_deposit', // Added
        'layaway_duration', // Added
        'amount_paid',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(Transaction::class, 'order_id');
    }

    public function layawayPayments()
    {
        return $this->hasMany(LayawayPayment::class);
    }

    
}
