<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use App\Mail\OrderPlaced;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Laravel\Facades\Image;

class CartController extends Controller
{
    public function addToCart(Request $request)
{
    try {
        // Validate the incoming request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id'
        ]);
        

        // Retrieve the authenticated user's ID
        $userId = Auth::id();

        // Check if the user already has this product with the specified variant in their cart
        $existingCartItem = CartItem::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existingCartItem) {
            // If the item exists, return a message and the updated cart count
            return response()->json([
                'success' => true,
                'message' => 'This item is already in your cart',
                'cartItemsCount' => CartItem::where('user_id', $userId)->count()
            ]);
        } else {
            // If the item doesn't exist, create a new cart item
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'quantity' => 1
            ]);

            // Return a success message and the updated cart count
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully!',
                'cartItemsCount' => CartItem::where('user_id', $userId)->count()
            ]);
        }
    } catch (\Exception $e) {
        // Return an error message in case of any exceptions
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while adding the item to the cart'
        ], 500);
    }
}

    public function getCartItemsCount()
    {
        $userId = auth()->id();
        $cartItemsCount = CartItem::where('user_id', $userId)->count();
    
        return response()->json([
            'cartItemsCount' => $cartItemsCount
        ]);
    }

    public function getCartData()
{
    // Retrieve the authenticated user's ID
    $userId = auth()->id();

    // Calculate number of items in the cart
    $cartItemsCount = CartItem::where('user_id', $userId)->count();

    // Calculate subtotal of all items in the cart based on product variants
    $cartSubtotal = CartItem::join('product_variants', 'cart_items.variant_id', '=', 'product_variants.id')
        ->where('cart_items.user_id', $userId)
        ->sum('product_variants.price');

    return response()->json([
        'cartItemsCount' => $cartItemsCount,
        'cartSubtotal' => $cartSubtotal,
    ]);
}

public function getItems()
{
    $cartItems = CartItem::with(['variant.product', 'variant.images'])
        ->where('user_id', auth()->id())
        ->get();

    return response()->json(['cartItems' => $cartItems]);
}





    public function deleteItem(Request $request)
{
    // Get the cart item ID from the request
    $cartItemId = $request->input('cartItemId');

    // Make sure the cart item exists
    $cartItem = CartItem::find($cartItemId);

    if (!$cartItem) {
        // If the cart item does not exist, return an error response
        return response()->json(['error' => 'Cart item not found'], 404);
    }

    // Delete the cart item
    $cartItem->delete();

    // Return a success response
    return response()->json(['success' => true]);
}

public function updateAndCheckout(Request $request) {
    // Get the quantities and cart IDs
    $quantities = $request->input('quantity');
    $cartIds = $request->input('cart_id');

    // Calculate total amount and update the cart items
    $totalAmount = 0;
    foreach ($quantities as $index => $quantity) {
        $cartItemId = $cartIds[$index];
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->quantity = $quantity;
        $cartItem->save();

        // Update total amount
        $totalAmount += $cartItem->variant->price * $quantity;
    }

    // Get cart items for the checkout
    $cartItems = CartItem::whereIn('id', $cartIds)->get();

    // Get the user's address from the users table
    $user = auth()->user();
    $userAddress = [
        'name' => $user->name,
        'street_address' => $user->street_address,
        'city' => $user->city,
        'province' => $user->province,
        'postal_code' => $user->postal_code,
        'phone_number' => $user->phone_number,
    ];

    // Pass cart items, total amount, and user address to the view
    return view('checkout', [
        'cartItems' => $cartItems,
        'totalAmount' => $totalAmount,
        'userAddress' => $userAddress,
    ]);
}

public function showCheckout() {
    // Get the user's cart items
    $cartItems = CartItem::where('user_id', auth()->id())->get();

    // Calculate the total amount
    $totalAmount = $cartItems->sum(function ($cartItem) {
        return $cartItem->variant->price * $cartItem->quantity;
    });

    // Get the user's address
    $user = auth()->user();
    $userAddress = [
        'name' => $user->name,
        'street_address' => $user->street_address,
        'city' => $user->city,
        'province' => $user->province,
        'postal_code' => $user->postal_code,
        'phone_number' => $user->phone_number,
    ];

    // Return the checkout view with cart items, total amount, and user address
    return view('checkout', [
        'cartItems' => $cartItems,
        'totalAmount' => $totalAmount,
        'userAddress' => $userAddress,
    ]);
}

public function payment(Request $request) {
    // Validate the common request data
    $validatedData = $request->validate([
        'payment_receipt' => 'required|file|mimes:jpeg,png,pdf|max:5120',
        'shipping_instructions' => 'required|string|max:255',
    ]);

    // Check for layaway toggle
    $layawayToggle = $request->has('layaway_toggle') && $request->input('layaway_toggle') == 'on';

    // Conditional validation for layaway fields
    if ($layawayToggle) {
        $validatedData = array_merge($validatedData, $request->validate([
            'layaway_deposit' => 'required|numeric|min:' . ($request->input('total_amount') * 0.2),
            'layaway_duration' => 'required|in:1,2,3',
        ]));
    }

    // Determine payment method
    $paymentMethod = $layawayToggle ? 'layaway' : 'fully paid';

    // Fetch cart items for the authenticated user
    $cartItems = CartItem::where('user_id', auth()->id())->get();

    // Retrieve shipping address based on user selection
    $shippingAddress = $request->input('address_option') == 'current_address' ? 
        $request->only(['fullname', 'street_address', 'city', 'province', 'postal_code', 'phone_number']) : 
        $request->only(['fullname_other', 'address_other', 'city_other', 'province_other', 'postal_code_other', 'phone_number_other']);

    // Generate order date
    $orderDate = now();

    // Calculate total amount from the form submission
    $totalAmount = $request->input('total_amount');

    // Calculate total number of items in the cart
    $totalItems = $cartItems->sum('quantity');

    // Begin a database transaction
    DB::beginTransaction();

    try {
        // Convert the receipt to WebP format
        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $receipt = $request->file('payment_receipt');
            $imageName = time() . '-' . uniqid() . '.webp';
            $webpImage = Image::read($receipt);
            $webpImage->save(public_path('storage/receipts/' . $imageName));
            $receiptPath = 'receipts/' . $imageName;
        }

        // Insert data into orders table
        $order = Order::create([
            'customer_id' => auth()->id(),
            'order_date' => $orderDate,
            'shipping_address' => json_encode($shippingAddress),
            'total_amount' => $totalAmount,
            'shipping_method' => 'TBD',
            'tracking_number' => 'TBD',
            'shipping_status' => 'preparing',
            'shipping_procedure' => $validatedData['shipping_instructions'],
            'num_orders' => $totalItems,
            'payment_method' => $paymentMethod,
            'layaway_deposit' => $paymentMethod == 'layaway' ? $request->input('layaway_deposit') : null,
            'layaway_duration' => $paymentMethod == 'layaway' ? $request->input('layaway_duration') : null,
            'receipt' => $receiptPath,
        ]);

        if ($paymentMethod == 'layaway') {
            // Add the first layaway payment record
            $order->layawayPayments()->create([
                'payment_date' => now(),
                'amount' => $request->input('layaway_deposit'),
                'status' => 'Pending', // Default status is pending
                'receipt' => $receiptPath, // Store the receipt path
                'is_initial_payment' => true, // Mark as initial payment
            ]);
        }

        foreach ($cartItems as $cartItem) {
            // Decrement the quantity of the product variant
            $productVariant = $cartItem->variant;
            $productVariant->quantity -= $cartItem->quantity;
            $productVariant->save();

            // Insert details into transactions table
            Transaction::create([
                'order_id' => $order->id,
                'variant_id' => $productVariant->id,
                'quantity' => $cartItem->quantity,
                'price' => $productVariant->price,
            ]);

            // If the product variant is out of stock, remove it from all other customers' carts
            if ($productVariant->quantity <= 0) {
                CartItem::where('variant_id', $productVariant->id)
                    ->where('user_id', '!=', auth()->id()) // Ensure it removes from other customers' carts
                    ->delete();
            }
        }

        // Commit the transaction
        DB::commit();

        // Send email notification
        try {
            $email = env('MAIL_USERNAME');
            Mail::to($email)->send(new OrderPlaced($order));
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
        }

        // Delete cart items after successful purchase
        $cartItems->each->delete();

        // Redirect to the dashboard
        return redirect()->route('checkOrders')->with('success', 'Checkout completed successfully!');
    } catch (\Exception $e) {
        // Rollback the transaction if an error occurs
        DB::rollBack();
        Log::error('Checkout Error: ' . $e->getMessage());
        // Handle the error gracefully
        return back()->with('error', 'An error occurred during checkout. Please try again later.');
    }
}













}
