<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\LayawayPayment;
use App\Models\ReservedItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservedItemArrived;


class AdminController extends Controller
{

    public function manageOrders()
{
    // Fetch orders with payment_method 'fully paid'
    $orders = Order::whereIn('payment_method', ['fully paid', 'Cancelled'])
    ->orderBy('id', 'desc')
    ->get();

    $totalOrdersCount = $orders->count();

    $newOrdersCount = $orders->where('shipping_status', 'preparing')->count();
    $pendingOrdersCount = $orders->where('shipping_status', 'shipped')->count();
    $deliveredOrdersCount = $orders->where('shipping_status', 'delivered')->count();
    $declinedOrdersCount = $orders->where('shipping_status', 'Declined')->count();

    // Calculate the impressions
    $newOrdersImpression = $totalOrdersCount > 0 ? round(($newOrdersCount / $totalOrdersCount) * 100, 2) : 0;
    $pendingOrdersImpression = $totalOrdersCount > 0 ? round(($pendingOrdersCount / $totalOrdersCount) * 100, 2) : 0;
    $deliveredOrdersImpression = $totalOrdersCount > 0 ? round(($deliveredOrdersCount / $totalOrdersCount) * 100, 2) : 0;
    $declinedOrdersImpression = $totalOrdersCount > 0 ? round(($declinedOrdersCount / $totalOrdersCount) * 100, 2) : 0;

    return view('admin.orders', [
        'orders' => $orders,
        'newOrdersCount' => $newOrdersCount,
        'pendingOrdersCount' => $pendingOrdersCount,
        'deliveredOrdersCount' => $deliveredOrdersCount,
        'declinedOrdersCount' => $declinedOrdersCount,
        'newOrdersImpression' => $newOrdersImpression,
        'pendingOrdersImpression' => $pendingOrdersImpression,
        'deliveredOrdersImpression' => $deliveredOrdersImpression,
        'declinedOrdersImpression' => $declinedOrdersImpression,
    ]);
}

    
    

    // Method for managing products
    public function manageProducts(Request $request)
    {
        $search = $request->query('search');
    
        $productsQuery = Product::query()
            ->orderBy('id', 'desc');
    
        if ($search) {
            // Split the search string into individual words
            $searchTerms = explode(' ', $search);
    
            $productsQuery->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->where(function ($query) use ($term) {
                        $query->where('name', 'like', '%' . $term . '%')
                        ->orWhere('description', 'like', '%' . $term . '%')
                              ->orWhere('brand', 'like', '%' . $term . '%');
                    });
                }
            });
        }
    
        $products = $productsQuery->get();
        $categories = Product::distinct()->pluck('category'); // Get distinct categories
    
        return view('admin.products', ['products' => $products, 'categories' => $categories]);
    }
    


    // Method for managing customers
    public function manageUser(Request $request)
    {
        // Retrieve the search query from the request
        $search = $request->input('search');
    
        // Fetch users with userType 'user' or 'banned'
        $query = User::whereIn('userType', ['user', 'banned']);
    
        // Apply search filter if the search query is not empty
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                      ->orWhere('email', 'LIKE', "%$search%")
                      ->orWhere('phone_number', 'LIKE', "%$search%");
            });
        }
    
        // Get the filtered users
        $users = $query->get();
    
        // Pass users data and search query to the view
        return view('admin.newUser', compact('users', 'search'));
    }
    

    public function manageInventory(Request $request)
{
    $search = $request->query('search');

    $productsQuery = Product::query()
        ->orderBy('id', 'desc');

    if ($search) {
        // Split the search string into individual words
        $searchTerms = explode(' ', $search);

        $productsQuery->where(function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%')
                          ->orWhere('brand', 'like', '%' . $term . '%');
                });
            }
        });
    }

    $products = $productsQuery->get();

    return view('admin.inventory', compact('products'));
}

public function signup(Request $request)
{
    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'userType' => 'required|string',
    ]);

    // Handle validation failure
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Create a new user and set the necessary attributes
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->userType = $request->userType;
    $user->email_verified_at = now();  // Set email_verified_at to the current timestamp
    $user->save();

    // Redirect back with a success message
    return redirect()->route('newUser')->with('success', 'User created successfully');
}

public function banUser(User $user)
{
    // Update user's userType to 'banned'
    $user->userType = 'banned';
    $user->save();

    return redirect()->back()->with('success', 'User has been banned successfully');
}

public function unbanUser(User $user)
{
    // Update user's userType to 'user'
    $user->userType = 'user';
    $user->save();

    return redirect()->back()->with('success', 'User has been unbanned successfully');
}


public function getLayawayDetails(Order $order)
{
    // Load accepted and pending payments
    $order->load(['customer', 'layawayPayments' => function ($query) {
        $query->whereIn('status', ['Accepted', 'Pending']);
    }]);

    // Load declined payments separately
    $declinedPayments = $order->layawayPayments()->where('status', 'Declined')->get();
    
    $paymentsMade = $order->layawayPayments->count();
    $totalPayments = $order->layaway_duration * 2;

    return response()->json([
        'success' => true,
        'order' => $order,
        'total_payments' => $totalPayments,
        'amount_paid' => $order->amount_paid, // Include amount_paid in the response
        'declined_payments' => $declinedPayments, // Pass the declined payments
    ]);
}



public function updatePaymentStatus(Request $request)
{
    try {
        // Validate the request
        $request->validate([
            'payment_id' => 'required|exists:layaway_payments,id',
            'status' => 'required|in:Pending,Accepted,Declined',
            'decline_reason' => 'nullable|string|max:255', // Validate decline_reason if present
        ]);

        // Find the payment
        $payment = LayawayPayment::find($request->payment_id);

        // Update the status
        $payment->status = $request->status;

        // If the status is declined, save the decline reason
        if ($request->status === 'Declined') {
            $payment->decline_reason = $request->input('decline_reason');
        }

        $payment->save();

        // If the payment is accepted, update the order's amount_paid
        if ($request->status === 'Accepted') {
            $order = $payment->order;
            $order->amount_paid += $payment->amount;
            $order->save();
        }

        // Return a JSON response indicating success
        return response()->json(['success' => true, 'message' => 'Payment status updated successfully!']);
    } catch (\Exception $e) {
        // Log the error message
        Log::error('Error updating payment status: ' . $e->getMessage());

        // Return an error response
        return response()->json(['success' => false, 'message' => 'An error occurred while updating the payment status.']);
    }
}




public function markAsFullyPaid(Order $order)
{
    // Calculate total amount paid
    $amountPaid = $order->layawayPayments->where('status', 'Accepted')->sum('amount');
    
    // Update order to fully paid
    $order->payment_method = 'fully paid';
    $order->amount_paid = $amountPaid;
    $order->save();

    return response()->json(['success' => true, 'message' => 'Order marked as fully paid successfully!']);
}

public function cancelOrder(Order $order)
{
    try {
        // Restore product stock before updating the order status
        foreach ($order->items as $item) {
            $item->variant->increment('quantity', $item->quantity);
        }

        // Update the order's shipping status to "Declined"
        $order->shipping_status = 'Declined';
        $order->payment_method = 'Cancelled';
        $order->save();

        return response()->json(['success' => true, 'message' => 'Order declined and stock restored successfully!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}

public function reviewReservation(Request $request, $id)
{
    $reservation = ReservedItem::findOrFail($id);
    
    $action = $request->input('action');
    if ($action === 'accept') {
        $reservation->status = 'Downpayment Accepted';
    } elseif ($action === 'decline') {
        $reservation->status = 'Declined';

        // Update the quantity of the variant for the declined reservation
        $variant = $reservation->variant;
        $variant->quantity += $reservation->quantity;
        $variant->save();
    }
    
    $reservation->save();

    return redirect()->back()->with('success', 'Reservation status updated successfully!');
}
public function markAsArrived($id)
{
    $product = Product::findOrFail($id);
    $product->is_upcoming = 0;
    $product->save();

    // Get all reserved items associated with this product
    $reservedItems = ReservedItem::where('product_id', $id)->get();

    foreach ($reservedItems as $reservedItem) {
        // Skip updating if the status is "Declined"
        if ($reservedItem->status === 'Declined') {
            continue;
        }

        // Update the status to "Arrived at Store"
        $reservedItem->status = 'Arrived at Store';
        $reservedItem->save();

        // Send email notification to the user
        Mail::to($reservedItem->user->email)->send(new ReservedItemArrived($reservedItem));
    }

    return response()->json(['success' => true, 'message' => 'Product marked as arrived, and notifications sent to users.']);
}

public function markAllAsArrived()
{
    $products = Product::where('is_upcoming', 1)->get();
    $products->each(function ($product) {
        $product->is_upcoming = 0;
        $product->save();

        // Get all reserved items associated with this product
        $reservedItems = ReservedItem::where('product_id', $product->id)->get();

        foreach ($reservedItems as $reservedItem) {
            // Skip updating if the status is "Declined"
            if ($reservedItem->status === 'Declined') {
                continue;
            }

            // Update the status to "Arrived at Store"
            $reservedItem->status = 'Arrived at Store';
            $reservedItem->save();

            // Send email notification to the user
            Mail::to($reservedItem->user->email)->send(new ReservedItemArrived($reservedItem));
        }
    });

    return response()->json(['success' => true, 'message' => 'All upcoming products marked as arrived, and notifications sent to users.']);
}
}
