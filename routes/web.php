<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Transaction;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;


//index
Route::get('/', function () {
    // Get all products with their associated images where quantity is greater than 0
    $products = Product::with(['variants' => function ($query) {
        $query->where('quantity', '>', 0)->with('images');
    }])->whereHas('variants', function ($query) {
        $query->where('quantity', '>', 0);
    })->get();

    return view('welcome', ['products' => $products]);
});

// User side
Route::get('/dashboard', function () {
    // Get products with their associated images where quantity is greater than 0, ordered by ID in descending order
    $products = Product::with(['variants' => function ($query) {
        $query->where('quantity', '>', 0)->with('images');
    }])
    ->whereHas('variants', function ($query) {
        $query->where('quantity', '>', 0);
    })
    ->orderBy('id', 'desc') // Order by ID in descending order
    ->get();
    


    // Calculate number of items in the cart
    $cartItemsCount = CartItem::where('user_id', auth()->id())->count();
    $cartItems = CartItem::with('product')->where('user_id', auth()->id())->get(); // Include the product relationship

    return view('dashboard', [
        'products' => $products,
        'cartItemsCount' => $cartItemsCount,
        'cartItems' => $cartItems,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'admin'])->group(function () {
//admin side
Route::get('/admindashboard', function () {
    // Sales Data
    $totalRevenue = Order::sum('total_amount');
    $totalSales = Order::count();
    $totalItemsSold = Transaction::sum('quantity');
    $averageOrderValue = $totalSales ? $totalRevenue / $totalSales : 0;
    
    $layawayOrders = Order::where('payment_method', 'layaway')
    ->with('customer')
    ->orderBy('id', 'desc')
    ->get();


    $topSellingProducts = Transaction::select('variant_id', DB::raw('SUM(quantity) as total_quantity'))
        ->groupBy('variant_id')
        ->orderByDesc('total_quantity')
        ->take(5)
        ->with('variant.product')
        ->get();

    $salesByCategory = Product::select('category', DB::raw('SUM(transactions.quantity * transactions.price) as total_sales'))
        ->join('product_variants', 'product_variants.product_id', '=', 'products.id')
        ->join('transactions', 'transactions.variant_id', '=', 'product_variants.id')
        ->groupBy('category')
        ->get();

    // Customer Data
    $totalCustomers = User::where('userType', 'user')->count();
    $newCustomers = User::where('userType', 'user')
        ->where('created_at', '>=', now()->subMonth())
        ->count(); // New customers in the last month

    $topCustomers = User::select('users.id', 'users.name', 'users.email', DB::raw('SUM(orders.total_amount) as total_spent'))
        ->join('orders', 'orders.customer_id', '=', 'users.id')
        ->where('users.userType', 'user')
        ->groupBy('users.id', 'users.name', 'users.email')
        ->orderByDesc('total_spent')
        ->take(5)
        ->get();

    // Purchase Frequency (average number of purchases per customer)
    $totalOrders = Order::count();
    $purchaseFrequency = $totalCustomers ? $totalOrders / $totalCustomers : 0;

    // Customer Retention Rate
    $repeatCustomers = User::where('userType', 'user')
        ->whereHas('orders', function ($query) {
            $query->groupBy('customer_id')->havingRaw('COUNT(*) > 1');
        })
        ->count();
    $customerRetentionRate = $totalCustomers ? ($repeatCustomers / $totalCustomers) * 100 : 0;

    // Inventory Data
    $totalProducts = ProductVariant::count();
    $productsInStock = ProductVariant::where('quantity', '>', 0)->count();
    $outOfStockProducts = ProductVariant::where('quantity', '=', 0)->count();
    $totalInventoryValue = ProductVariant::sum(DB::raw('price * quantity'));
    
    $stockedProducts = ProductVariant::select('product_id', 'color', 'size', DB::raw('SUM(quantity) as total_quantity'))
    ->groupBy('product_id', 'color', 'size')
    ->orderBy('total_quantity')
    ->with('product')
    ->get();


    $productsByCategory = Product::select('category', DB::raw('COUNT(*) as total_products'))
        ->groupBy('category')
        ->get();

    // Inventory Turnover Rate (assuming a period, e.g., last 12 months)
    $costOfGoodsSold = Transaction::sum(DB::raw('quantity * price')); // Total cost of goods sold
    $averageInventory = ProductVariant::avg(DB::raw('price * quantity')); // Average inventory value
    $inventoryTurnoverRate = $averageInventory ? $costOfGoodsSold / $averageInventory : 0;

    // Pass the data to the view
    return view('admin.admindashboard', [
        'totalRevenue' => $totalRevenue,
        'totalSales' => $totalSales,
        'totalItemsSold' => $totalItemsSold,
        'averageOrderValue' => $averageOrderValue,
        'topSellingProducts' => $topSellingProducts,
        'salesByCategory' => $salesByCategory,
        'totalCustomers' => $totalCustomers,
        'newCustomers' => $newCustomers,
        'topCustomers' => $topCustomers,
        'purchaseFrequency' => $purchaseFrequency,
        'customerRetentionRate' => $customerRetentionRate,
        'totalProducts' => $totalProducts,
        'productsInStock' => $productsInStock,
        'outOfStockProducts' => $outOfStockProducts,
        'totalInventoryValue' => $totalInventoryValue,
        'stockedProducts' => $stockedProducts,
        'productsByCategory' => $productsByCategory,
        'inventoryTurnoverRate' => $inventoryTurnoverRate,
        'layawayOrders' => $layawayOrders, // Add this line
    ]);
})->name('admindashboard');


Route::get('/admin/orders', [AdminController::class, 'manageOrders'])->name('order');
Route::get('/admin/products', [AdminController::class, 'manageProducts'])->name('product');
Route::get('/admin/user', [AdminController::class, 'manageUser'])->name('newUser');
Route::get('/admin/inventory', [AdminController::class, 'manageInventory'])->name('inventory');
Route::post('/admin/signup', [AdminController::class, 'signup'])->name('admin.signup');
Route::post('/admin/ban/{user}', [AdminController::class, 'banUser'])->name('admin.banUser');
Route::post('/admin/unban/{user}', [AdminController::class, 'unbanUser'])->name('admin.unbanUser');

});

//product section
Route::post('/storeproduct', [ProductController::class, 'store'])->name('products.store');
Route::get('/show/{id}/{variant_id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{id}/{variant_id}', [ProductController::class, 'edit'])->name('products.edit');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::patch('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::post('/products/addImage/', [ProductController::class, 'addImage'])->name('products.addImage');
Route::delete('/products/{image}/deleteImage', [ProductController::class, 'deleteImage'])->name('products.deleteImage');
Route::get('/search', [ProductController::class, 'search'])->name('product.search');
Route::post('/addvariant', [ProductController::class, 'addvariant'])->name('products.addvariant');
Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');



// Modify profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/orders', function () {
    // Retrieve orders associated with the currently authenticated user
    $orders = Order::where('customer_id', Auth::id())->orderBy('id', 'desc')->get();

    // Pass the orders data to the view
    return view('orders', ['orders' => $orders]);
})->name('checkOrders');


//inventory section
Route::get('/admin/update-stock/{id}/{variant_id}', [ProductController::class, 'updateStock'])->name('admin.updateStock');
Route::get('/admin/mark-as-sold/{id}/{variant_id}', [ProductController::class, 'markAsSold'])->name('admin.markAsSold');
Route::post('/admin/add-stock/{id}', [ProductController::class, 'addStock'])->name('admin.addStock');
Route::patch('/admin/sold/{productId}/{variantId}', [ProductController::class, 'sold'])->name('admin.sold');



//add to cart
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/items/count', [CartController::class, 'getCartItemsCount'])->name('cart.items.count');
Route::get('/cart/data', [CartController::class, 'getCartData'])->name('cart.data');
Route::get('/cart/items', 'App\Http\Controllers\CartController@getItems')->name('cart.items');
Route::post('/cart/delete', 'App\Http\Controllers\CartController@deleteItem')->name('cart.delete');
Route::patch('/cart/checkout', [CartController::class, 'updateAndCheckout'])->name('cart.updateAndCheckout');






//checkout
Route::post('/cart/payment', [CartController::class, 'payment'])->name('cart.payment');


//orders
Route::get('/get-orders/{orderId}', [OrderController::class, 'getOrders'])->name('getOrders');
Route::get('/get-status/{orderId}', [OrderController::class, 'getStatus'])->name('getStatus');
Route::patch('/update-status/{orderId}', [OrderController::class, 'updateStatus'])->name('updateStatus');
Route::get('/view-items/{orderId}', [OrderController::class, 'viewItems'])->name('viewItems');
Route::get('/filter-orders/{status}', [OrderController::class, 'filterOrders'])->name('filterOrders');
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::get('/layaway-payments/{order}', [OrderController::class, 'layawayPayments'])->name('layaway.payments');
Route::post('/add-layaway-payment', [OrderController::class, 'addLayawayPayment']);



Route::get('/admin/layaway-details/{order}', [AdminController::class, 'getLayawayDetails']);
Route::post('/admin/update-payment-status', [AdminController::class, 'updatePaymentStatus']);

Route::post('/admin/mark-as-fully-paid/{order}', [AdminController::class, 'markAsFullyPaid']);












require __DIR__ . '/auth.php';
