<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\LayawayPayment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{

    public function manageOrders()
{
    // Fetch orders with payment_method 'fully paid'
    $orders = Order::where('payment_method', 'fully paid')->orderBy('id', 'desc')->get();
    $totalOrdersCount = $orders->count();

    $newOrdersCount = $orders->where('shipping_status', 'preparing')->count();
    $pendingOrdersCount = $orders->where('shipping_status', 'shipped')->count();
    $deliveredOrdersCount = $orders->where('shipping_status', 'delivered')->count();

    // Calculate the impressions
    $newOrdersImpression = $totalOrdersCount > 0 ? round(($newOrdersCount / $totalOrdersCount) * 100, 2) : 0;
    $pendingOrdersImpression = $totalOrdersCount > 0 ? round(($pendingOrdersCount / $totalOrdersCount) * 100, 2) : 0;
    $deliveredOrdersImpression = $totalOrdersCount > 0 ? round(($deliveredOrdersCount / $totalOrdersCount) * 100, 2) : 0;

    return view('admin.orders', [
        'orders' => $orders,
        'newOrdersCount' => $newOrdersCount,
        'pendingOrdersCount' => $pendingOrdersCount,
        'deliveredOrdersCount' => $deliveredOrdersCount,
        'newOrdersImpression' => $newOrdersImpression,
        'pendingOrdersImpression' => $pendingOrdersImpression,
        'deliveredOrdersImpression' => $deliveredOrdersImpression,
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
    $order->load('customer', 'layawayPayments');
    $paymentsMade = $order->layawayPayments->count();
    $totalPayments = $order->layaway_duration * 2;

    return response()->json([
        'success' => true,
        'order' => $order,
        'total_payments' => $totalPayments,
        'amount_paid' => $order->amount_paid, // Include amount_paid in the response
    ]);
}


public function updatePaymentStatus(Request $request)
{
    $request->validate([
        'payment_id' => 'required|exists:layaway_payments,id',
        'status' => 'required|in:pending,Accepted,rejected',
    ]);

    $payment = LayawayPayment::find($request->payment_id);
    $payment->status = $request->status;
    $payment->save();

    // If the payment is accepted, add the payment amount to the order's amount_paid
    if ($request->status === 'Accepted') {
        $order = $payment->order;
        $order->amount_paid += $payment->amount;
        $order->save();
    }

    return response()->json(['success' => true, 'message' => 'Payment status updated successfully!']);
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


}
