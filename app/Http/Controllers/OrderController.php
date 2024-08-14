<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\LayawayPayment;
use App\Models\ProductVariant;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    try {
        // Retrieve the order details along with customer information
        $order = Order::with('customer')->findOrFail($id);

        // Retrieve items for the specified order
        $transactions = Transaction::where('order_id', $id)->get();

        // Initialize an array to store items with additional details
        $items = [];

        // Loop through each transaction to fetch item details
        foreach ($transactions as $transaction) {
            // Get the product variant details including the associated product and images
            $productVariant = ProductVariant::with('product', 'images')->find($transaction->variant_id);

            // Create an array with item details including product name, brand, size, color, quantity, and image paths
            $itemDetails = [
                'product_name' => $productVariant->product->name,
                'product_brand' => $productVariant->product->brand,
                'size' => $productVariant->size,
                'color' => $productVariant->color,
                'quantity' => $transaction->quantity,
                'image_paths' => $productVariant->images->pluck('path')->toArray(),
            ];

            // Add the item details to the items array
            $items[] = $itemDetails;
        }

        // Return the order details and items as JSON response
        return response()->json(['success' => true, 'order' => $order, 'items' => $items]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Order not found', 'error' => $e->getMessage()]);
    }
}

    




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getOrders($orderId)
    {
        // Retrieve items for the specified order
        $transactions = Transaction::where('order_id', $orderId)->get();
    
        // Initialize an array to store items with additional details
        $items = [];
    
        // Loop through each transaction to fetch item details
        foreach ($transactions as $transaction) {
            // Get the product variant details including the associated product and images
            $productVariant = ProductVariant::with('images')->find($transaction->variant_id);
    
            // Create an array with item details including product name, brand, size, color, quantity, and image paths
            $itemDetails = [
                'product_name' => $productVariant->product->name,
                'product_brand' => $productVariant->product->brand,
                'size' => $productVariant->size,
                'color' => $productVariant->color,
                'quantity' => $transaction->quantity,
                'image_paths' => $productVariant->images->pluck('path')->toArray(),
            ];
    
            // Add the item details to the items array
            $items[] = $itemDetails;
        }
    
        // Return the items as JSON response
        return response()->json(['success' => true, 'items' => $items]);
    }


    public function getStatus($orderId)
    {
        $order = Order::find($orderId);
    
        if ($order) {
            return response()->json([
                'success' => true,
                'shipping_method' => $order->shipping_method,
                'tracking_number' => $order->tracking_number,
                'shipping_status' => $order->shipping_status,
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }
    }

    public function updateStatus(Request $request, $orderId)
{
    $order = Order::find($orderId);

    if ($order) {
        $order->shipping_method = $request->input('shipping_method');
        $order->tracking_number = $request->input('tracking_number');
        $order->shipping_status = $request->input('shipping_status');
        $order->save();

        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false, 'message' => 'Order not found']);
    }
}

public function viewItems($orderId)
{
    // Retrieve transactions associated with the order ID
    $transactions = Transaction::where('order_id', $orderId)->get();

    // Initialize an array to store items with additional details
    $items = [];

    // Loop through each transaction to fetch item details
    foreach ($transactions as $transaction) {
        // Get the product variant details including the associated product and images
        $productVariant = ProductVariant::with('product', 'images')->find($transaction->variant_id);

        // Ensure product variant exists
        if ($productVariant) {
            // Create an array with item details including product name, brand, size, color, quantity, and image paths
            $itemDetails = [
                'product_name' => $productVariant->product->name,
                'product_brand' => $productVariant->product->brand,
                'size' => $productVariant->size,
                'color' => $productVariant->color,
                'quantity' => $transaction->quantity,
                'image_paths' => $productVariant->images->pluck('path')->toArray(),
            ];

            // Add the item details to the items array
            $items[] = $itemDetails;
        }
    }

    // Return the items as JSON response
    return response()->json(['success' => true, 'items' => $items]);
}


public function filterOrders($status)
{
    if ($status == 'all') {
        $orders = Order::with('customer')
            ->where('payment_method', 'fully paid')
            ->orderBy('id', 'desc')
            ->get();
    } else {
        $orders = Order::with('customer')
            ->where('payment_method', 'fully paid')
            ->where('shipping_status', $status)
            ->orderBy('id', 'desc')
            ->get();
    }

    return response()->json($orders);
}


    
public function layawayPayments(Order $order)
{
    // Calculate total payments required (excluding the initial down payment)
    $totalPaymentsRequired = $order->layaway_duration * 2;
    $months = $order->layaway_duration;

    // Fetch all layaway payments
    $allPayments = $order->layawayPayments()->get();

    // Separate initial payment and other payments
    $initialPayment = $allPayments->where('is_initial_payment', true)->first();
    $payments = $allPayments->where('is_initial_payment', false);

    // Calculate the number of payments made (excluding the initial down payment)
    $paymentsMade = $payments->count();

    // Calculate the remaining balance (excluding the initial down payment)
    $remainingBalance = $order->total_amount - $order->amount_paid;
    $nextPaymentAmount = $remainingBalance / ($totalPaymentsRequired - $paymentsMade);

    // Calculate the next payment due date using the new logic
    $today = Carbon::today();
    $lastPaymentDate = $payments->isEmpty() ? $today : $payments->last()->payment_date;
    $lastPaymentDate = Carbon::parse($lastPaymentDate);

    // Determine the next payment date
    if ($lastPaymentDate->day <= 15) {
        $nextPaymentDate = $lastPaymentDate->copy()->endOfMonth(); // Set to the end of the month
    } else {
        $nextPaymentDate = $lastPaymentDate->copy()->addMonthNoOverflow()->day(15); // Set to 15th of next month
    }

    return response()->json([
        'success' => true,
        'initialPayment' => $initialPayment,
        'payments' => $payments,
        'paymentsMade' => $paymentsMade,
        'totalPaymentsRequired' => $totalPaymentsRequired,
        'months' => $months,
        'nextPaymentDate' => $nextPaymentDate->format('F j, Y'),
        'nextPaymentAmount' => $nextPaymentAmount,
        'totalAmount' => $order->total_amount,
        'remainingBalance' => $remainingBalance,
        'amount_paid' => $order->amount_paid,
        'allPayments' => $allPayments,
    ]);
}


public function addLayawayPayment(Request $request)
{
    $validatedData = $request->validate([
        'payment_receipt' => 'required|file|mimes:jpeg,png,pdf|max:5120',
        'order_id' => 'required|exists:orders,id',
        'payment_amount' => 'required|numeric|min:1',
    ]);

    $order = Order::find($request->input('order_id'));

    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Order not found.']);
    }

    $receiptPath = $request->file('payment_receipt')->store('receipts', 'public');
    $paymentAmount = $request->input('payment_amount');

    $isInitialPayment = $order->layawayPayments()->count() === 0;

    LayawayPayment::create([
        'order_id' => $order->id,
        'payment_date' => Carbon::now(),
        'amount' => $paymentAmount,
        'status' => 'Pending',
        'receipt' => $receiptPath,
        'is_initial_payment' => $isInitialPayment,
    ]);

    return response()->json(['success' => true, 'message' => 'Payment submitted successfully!', 'order_id' => $order->id]);
}





}
