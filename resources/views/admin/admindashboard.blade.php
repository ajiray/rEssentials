@extends('layouts.adminlayout')

@section('content')
    <div class="w-full h-auto py-20 flex justify-center items-center">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Sales Report Button -->
            <div class="flex justify-center items-center flex-col p-6 rounded-lg bg-emerald-500 hover:bg-emerald-600 cursor-pointer text-white transition duration-300 ease-in-out transform hover:scale-105"
                onclick="document.getElementById('sales_report_modal').showModal()">
                <i class="fa-solid fa-cash-register text-4xl mb-2"></i>
                <p class="text-lg font-semibold">Sales Report</p>
            </div>


            <!-- Customer Report Button -->
            <div class="flex justify-center items-center flex-col p-6 rounded-lg bg-purple-500 hover:bg-purple-600 cursor-pointer text-white transition duration-300 ease-in-out transform hover:scale-105"
                onclick="document.getElementById('customer_report_modal').showModal()">
                <i class="fa-solid fa-users text-4xl mb-2"></i>
                <p class="text-lg font-semibold">Customer Report</p>
            </div>

            <!-- Inventory Report Button -->
            <div class="relative flex justify-center items-center flex-col p-6 rounded-lg bg-red-500 hover:bg-red-600 cursor-pointer text-white transition duration-300 ease-in-out transform hover:scale-105"
                onclick="document.getElementById('inventory_report_modal').showModal()">
                <i class="fa-solid fa-warehouse text-4xl mb-2"></i>
                <p class="text-lg font-semibold">Inventory Report</p>

                <!-- Warning Badge for Out-of-Stock Products -->
                @if ($outOfStockProducts > 0)
                    <span
                        class="absolute top-0 right-0 transform translate-x-3 -translate-y-3 flex items-center justify-center w-10 h-10 text-2xl font-bold leading-none text-white bg-red-700 rounded-full">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                    </span>
                @endif
            </div>

          <!-- Refund Requests Button -->
<div class="relative flex justify-center items-center flex-col p-6 rounded-lg bg-indigo-500 hover:bg-indigo-600 cursor-pointer text-white transition duration-300 ease-in-out transform hover:scale-105"
onclick="document.getElementById('refund_requests_modal').showModal()">
<i class="fa-solid fa-receipt text-4xl mb-2"></i>
<p class="text-lg font-semibold">Refund Requests</p>

<!-- Badge showing the count of refund requests with 'requested' status -->
@php
    $requestedRefundsCount = $refundRequests->where('refund_status', 'requested')->count();
@endphp

@if ($requestedRefundsCount > 0)
    <span class="absolute top-0 right-0 transform translate-x-2 -translate-y-2 flex items-center justify-center w-8 h-8 text-lg font-bold leading-none text-white bg-red-700 rounded-full">
        {{ $requestedRefundsCount }}
    </span>
@endif
</div>


            <!-- Layaway Orders Button -->
            <div class="relative flex justify-center items-center flex-col p-6 rounded-lg bg-amber-500 hover:bg-amber-600 cursor-pointer text-white transition duration-300 ease-in-out transform hover:scale-105"
                onclick="document.getElementById('layaway_orders_modal').showModal()">
                <i class="fa-solid fa-wallet text-4xl mb-2"></i>
                <p class="text-lg font-semibold">Layaway Orders</p>

                <!-- Badge for Layaway Orders Count -->
                <span
                    class="absolute top-0 right-0 transform translate-x-2 -translate-y-2 flex items-center justify-center w-8 h-8 text-lg font-bold leading-none text-white bg-orange-700 rounded-full">
                    {{ $layawayOrders->count() }}
                </span>
            </div>



        </div>
    </div>

    <!-- Sales Report Modal -->
    <dialog id="sales_report_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl max-h-85vh p-10">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                onclick="document.getElementById('sales_report_modal').close()">✕</button>
            <h3 class="font-bold text-3xl text-center mb-6">Sales Report</h3>

            <div id="sales_report_content" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Total Revenue:</p>
                    <p class="text-2xl text-green-500">₱{{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Total Sales:</p>
                    <p class="text-2xl text-green-500">{{ $totalSales }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Average Order Value:</p>
                    <p class="text-2xl text-green-500">₱{{ number_format($averageOrderValue, 2) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Total Items Sold:</p>
                    <p class="text-2xl text-green-500">{{ $totalItemsSold }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Total Declined/Cancelled Orders:</p>
                    <p class="text-2xl text-green-500">{{ $totalDeclined }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Top Selling Products:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($topSellingProducts as $transaction)
                            @if ($transaction->variant && $transaction->variant->product)
                                <li>{{ $transaction->variant->product->brand }} {{ $transaction->variant->product->name }}
                                    ({{ $transaction->variant->color }} {{ $transaction->variant->size }})
                                    -
                                    {{ $transaction->total_quantity }} units
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Sales by Category:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($salesByCategory as $category)
                            <li>{{ $category->category }} - ₱{{ number_format($category->total_sales, 2) }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </dialog>

    <!-- Refund Requests Modal -->
    <dialog id="refund_requests_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl max-h-85vh p-10">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                onclick="document.getElementById('refund_requests_modal').close()">✕</button>
            <h3 class="font-bold text-3xl text-center mb-6">Refund Requests</h3>
    
            <!-- Table for Refund Requests -->
            <div id="refund_requests_content" class="grid grid-cols-1 gap-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-center">
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Refund Reason</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Details</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="refund_requests_body" class="bg-white divide-y divide-gray-200">
                        <!-- Loop through refund requests -->
                        @foreach ($refundRequests as $order)
                            <tr class="text-center">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->customer->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->refund_reason }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($order->refund_payment_method) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->refund_payment_details }}</td>
                                
                                <!-- Status with color change -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($order->refund_status == 'requested')
                                        <span class="text-red-600 font-semibold">Requested</span>
                                    @elseif ($order->refund_status == 'processed')
                                        <span class="text-emerald-600 font-semibold">Processed</span>
                                    @endif
                                </td>
                                
                                <!-- Process Refund button (hidden if already processed) -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if ($order->refund_status == 'requested')
                                        <button class="text-blue-600 hover:text-blue-800 font-semibold"
                                            onclick="document.getElementById('refund_modal_{{ $order->id }}').showModal()">Process Refund</button>
                                        
                                        <!-- Refund Modal -->
                                        <dialog id="refund_modal_{{ $order->id }}" class="modal">
                                            <div class="modal-box">
                                                <form method="POST" action="{{ route('process.refund', $order->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <h3 class="font-bold text-lg">Process Refund for Order #{{ $order->id }}</h3>
                                                    <p class="py-4">Please upload the receipt for the refund.</p>
    
                                                    <input type="file" name="refund_receipt" accept="image/*" required
                                                        class="file-input file-input-bordered w-full max-w-xs mb-4" />
    
                                                    <div class="modal-action">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                        <button type="button" class="btn" onclick="document.getElementById('refund_modal_{{ $order->id }}').close()">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </dialog>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </dialog>




    <!-- Customer Report Modal -->
    <dialog id="customer_report_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl max-h-85vh p-10">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                onclick="document.getElementById('customer_report_modal').close()">✕</button>
            <h3 class="font-bold text-3xl text-center mb-6">Customer Report</h3>

            <div id="customer_report_content" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Number of Customers -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Total Number of Customers:</p>
                    <p class="text-2xl text-blue-500">{{ $totalCustomers }}</p>
                </div>
                <!-- New Customers -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">New Customers:</p>
                    <p class="text-2xl text-green-500">{{ $newCustomers }}</p>
                </div>

                <!-- Top Customers by Revenue -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Top Customers by Revenue:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($topCustomers as $customer)
                            <li>{{ $customer->name }} - ₱{{ number_format($customer->total_spent, 2) }}</li>
                        @endforeach
                    </ul>
                </div>
                <!-- Purchase Frequency -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Purchase Frequency:</p>
                    <p class="text-2xl text-blue-500">{{ number_format($purchaseFrequency, 2) }} purchases/customer</p>
                </div>
                <!-- Customer Retention Rate -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Customer Retention Rate:</p>
                    <p class="text-2xl text-green-500">{{ number_format($customerRetentionRate, 2) }}%</p>
                </div>
            </div>
        </div>
    </dialog>



    <!-- Inventory Report Modal -->
    <dialog id="inventory_report_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl max-h-85vh p-10">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                onclick="document.getElementById('inventory_report_modal').close()">✕</button>
            <h3 class="font-bold text-3xl text-center mb-6">Inventory Report</h3>

            <div id="inventory_report_content" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Products -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Total Products:</p>
                    <p class="text-2xl text-blue-500">{{ $totalProducts }}</p>
                </div>
                <!-- Products in Stock -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Products in Stock:</p>
                    <p class="text-2xl text-green-500">{{ $productsInStock }}</p>
                </div>
                <!-- Out of Stock Products -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Out of Stock Products:</p>
                    <p class="text-2xl text-red-500">{{ $outOfStockProducts }}</p>
                </div>
                <!-- Total Inventory Value -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Total Inventory Value:</p>
                    <p class="text-2xl text-green-500">₱{{ number_format($totalInventoryValue, 2) }}</p>
                </div>

                <!-- Products by Category -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Products by Category:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($productsByCategory as $category)
                            <li>{{ $category->category }} - {{ $category->total_products }} products</li>
                        @endforeach
                    </ul>
                </div>
                <!-- Inventory Turnover Rate -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <p class="font-semibold text-lg mb-2">Inventory Turnover Rate:</p>
                    <p class="text-2xl text-green-500">{{ number_format($inventoryTurnoverRate, 2) }}</p>
                </div>

                <!-- Most Stocked Products -->
                <div class="bg-white rounded-lg shadow-md p-6 col-span-2 max-h-72 overflow-y-auto">
                    <p class="font-semibold text-lg mb-2">Products by Stock (Low to High):</p>
                    <ul class="list-disc list-inside">
                        @foreach ($stockedProducts as $product)
                            <li>{{ $product->product->brand }} {{ $product->product->name }} ({{ $product->color }}
                                {{ $product->size }}) - {{ $product->total_quantity }} units</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </dialog>

    <!-- Layaway Orders Modal -->
    <dialog id="layaway_orders_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl h-[80vh] p-10">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                onclick="document.getElementById('layaway_orders_modal').close()">✕</button>
            <h3 class="font-bold text-3xl text-center mb-6">Layaway Orders</h3>

            <div id="layaway_orders_content" class="grid grid-cols-1 gap-6">
                <!-- Layaway Orders List -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-center">
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order ID
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order Date
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Amount
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Progress
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="layaway_orders_body" class="bg-white divide-y divide-gray-200">
                        @foreach ($layawayOrders as $order)
                            <tr class="text-center">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($order->order_date)->format('F j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->customer->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ₱{{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->layawayPayments->filter(function ($payment) {
                                            return $payment->is_initial_payment == 0;
                                        })->count() }}/{{ $order->layaway_duration * 2 }}
                                </td>

                                @php
                                    $hasPendingPayment = $order->layawayPayments->contains('status', 'Pending');

                                    // Calculate the next payment due date based on the number of valid payments made
                                    $validPaymentsCount = $order->layawayPayments->where('status', 'Accepted')->count();
                                    $nextPaymentDueDate = \Carbon\Carbon::parse($order->order_date);

                                    // Determine the next payment due date using the same logic as in the JavaScript
                                    for ($i = 0; $i <= $validPaymentsCount; $i++) {
                                        if ($nextPaymentDueDate->day <= 15) {
                                            $nextPaymentDueDate->endOfMonth(); // Next payment at the end of the current month
                                        } else {
                                            $nextPaymentDueDate->addMonth()->day(15); // 15th of the next month
                                        }
                                    }

                                    $isDueDate = $nextPaymentDueDate->isPast();

                                    // Determine the button class based on the payment status
                                    $buttonClass = 'text-emerald-600 hover:text-emerald-900'; // Default to emerald
                                    if ($hasPendingPayment) {
                                        $buttonClass = 'text-amber-600 hover:text-amber-900';
                                    } elseif ($isDueDate) {
                                        $buttonClass = 'text-red-600 hover:text-red-900';
                                    }
                                @endphp

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="viewLayawayDetails({{ $order->id }})"
                                        class="{{ $buttonClass }}">
                                        View Details
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </dialog>

    <!-- Layaway Details Modal -->
    <dialog id="layaway_details_modal" class="modal z-40">
        <div class="modal-box w-11/12 max-w-5xl h-[80vh] p-10 relative flex flex-col justify-between">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                onclick="document.getElementById('layaway_details_modal').close()">✕</button>
            <h3 class="font-bold text-3xl text-center mb-6">Layaway Order Details</h3>

            <div id="layaway_details_content" class="text-center mb-4">
                <!-- Hidden input to store order ID -->
                <input type="hidden" id="layaway_order_id" name="layaway_order_id">

                <!-- Order Details -->
                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-6">
                    <div>
                        <p class="font-semibold text-gray-700">Customer:</p>
                        <p class="text-lg" id="customer_name"></p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Email:</p>
                        <p class="text-lg" id="email"></p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Phone Number:</p>
                        <p class="text-lg" id="number"></p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Order Date:</p>
                        <p class="text-lg" id="order_date"></p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Total Amount:</p>
                        <p class="text-lg text-green-600">₱<span id="total_amount"></span></p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Amount Paid:</p>
                        <p class="text-lg text-blue-600">₱<span id="amount_paid"></span></p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Remaining Balance:</p>
                        <p class="text-lg text-red-600"><span id="remaining_balance"></span></p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Payment Progress:</p>
                        <p class="text-lg" id="payment_progress"></p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Next Payment Amount:</p>
                        <p class="text-lg text-orange-600"><span id="next_payment_amount"></span></p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">Next Payment Due:</p>
                        <p class="text-lg" id="next_payment_due"></p>
                    </div>
                </div>


                <!-- Layaway Payments -->
                <h4 class="font-bold text-2xl mb-4">Payments</h4>
                <ul id="layaway_payments_list" class="space-y-4 mb-4">
                    <!-- Payments will be dynamically added here -->
                </ul>
            </div>


            <!-- Fully Paid Button -->
            <div class="p-4">
                <button class="btn btn-primary w-full text-white bg-emerald-600 hover:bg-emerald-700 border-0"
                    onclick="markAsFullyPaid()">Mark as Fully Paid</button>
            </div>

            <!-- Cancel Order Button -->
            <div class="p-4">
                <button class="btn btn-primary w-full text-white bg-red-600 hover:bg-red-700 border-0"
                    onclick="cancelOrder()">Cancel Order</button>
            </div>


            <!-- Image Display Div -->
            <div id="imageDisplay"
                class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
                <div class="relative">
                    <img id="displayImage" src="" alt="Receipt Image"
                        class="w-[700px] h-[700px] object-contain">
                    <button type="button"
                        class="absolute top-0 right-2 text-white text-3xl bg-transparent border-none cursor-pointer"
                        onclick="closeImageDisplay()">✕</button>
                </div>
            </div>
        </div>
    </dialog>




    <script>
        function markAsFullyPaid() {
            const orderId = document.getElementById('layaway_order_id').value;
            fetch(`/admin/mark-as-fully-paid/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order marked as fully paid successfully.');
                        document.getElementById('layaway_details_modal').close();
                        location.reload();
                    } else {
                        alert('An error occurred while marking the order as fully paid.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function cancelOrder() {
            const orderId = document.getElementById('layaway_order_id').value;
            fetch(`/admin/cancel-order/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order canceled successfully.');
                        document.getElementById('layaway_details_modal').close();
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function viewLayawayDetails(orderId) {
            fetch(`/admin/layaway-details/${orderId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modal = document.getElementById('layaway_details_modal');
                        if (modal) {
                            document.getElementById('layaway_order_id').value = orderId;
                            document.getElementById('customer_name').textContent = data.order.customer.name;
                            document.getElementById('email').textContent = data.order.customer.email;
                            document.getElementById('number').textContent = data.order.customer.phone_number;
                            document.getElementById('order_date').textContent = new Date(data.order.order_date)
                                .toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                });

                            // Convert total_amount and amount_paid to a number before using toFixed()
                            var totalAmount = Math.ceil(Number(data.order.total_amount) * 100) / 100;
                            document.getElementById('total_amount').textContent = totalAmount.toFixed(2);

                            var amountPaid = Math.ceil(Number(data.order.amount_paid) * 100) / 100;
                            document.getElementById('amount_paid').textContent = amountPaid.toFixed(2);

                            // Calculate Remaining Balance
                            var remainingBalance = Math.ceil((totalAmount - amountPaid) * 100) / 100;
                            document.getElementById('remaining_balance').textContent =
                                `₱${remainingBalance.toFixed(2)}`;

                            // Ensure data.order.layaway_payments is an array
                            var payments = Array.isArray(data.order.layaway_payments) ? data.order.layaway_payments :
                        [];
                            var declinedPayments = Array.isArray(data.declined_payments) ? data.declined_payments : [];

                            // Filter payments to count only those with is_initial_payment == 0
                            var validPaymentsCount = payments.filter(payment => payment.is_initial_payment == 0).length;

                            document.getElementById('payment_progress').textContent =
                                `${validPaymentsCount}/${data.order.layaway_duration * 2}`;

                            // Calculate Next Payment Due Date
                            var nextPaymentDueDate = getNextPaymentDueDate(validPaymentsCount);
                            document.getElementById('next_payment_due').textContent = nextPaymentDueDate.format(
                                'MMMM D, YYYY');

                            // Calculate Next Payment Amount
                            var newRemainingPayments = (data.order.layaway_duration * 2) - validPaymentsCount;
                            var nextPaymentAmount = Math.ceil((remainingBalance / newRemainingPayments) * 100) / 100;
                            document.getElementById('next_payment_amount').textContent =
                                `₱${nextPaymentAmount.toFixed(2)}`;

                            var paymentsList = document.getElementById('layaway_payments_list');
                            paymentsList.innerHTML = '';

                            // Display accepted and pending payments
                            var paymentCounter = 1;
                            payments.forEach((payment) => {
                                var listItem = document.createElement('div');
                                listItem.classList.add('flex', 'justify-between', 'items-center', 'border',
                                    'border-gray-200', 'rounded-md', 'p-4', 'mb-2', 'bg-white', 'shadow-sm',
                                    'flex-col', 'lg:flex-row');

                                // Determine the payment label
                                var paymentLabel = payment.is_initial_payment == 1 ? 'Down Payment' :
                                    `Payment ${paymentCounter++}`;

                                // Determine the status color
                                var statusColorClass = payment.status === 'Pending' ? 'text-amber-600' :
                                    (payment.status === 'Declined' ? 'text-red-600' : 'text-emerald-600');

                                // Build the innerHTML
                                listItem.innerHTML = `
                            <div class="flex-1">
                                <p class="text-lg font-semibold mb-2 text-gray-800">${paymentLabel}</p>
                                <p class="text-md text-gray-600">Amount: ₱${Math.ceil(Number(payment.amount) * 100) / 100}</p>
                                <p class="text-md text-gray-600">Date: ${new Date(payment.payment_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                <p class="text-md text-gray-600">Status: <span class="${statusColorClass}">${payment.status}</span></p>
                            </div>
                            <img src="/storage/${payment.receipt}" alt="Receipt" class="w-24 h-24 cursor-pointer rounded-md object-cover lg:w-32 lg:h-32" onclick="showImageModal(this)">
                            ${payment.status === 'Pending' ? `
                                                                          <div class="flex flex-col space-y-3 items-center">
                                            <button class="btn btn-sm bg-emerald-500 ml-4" onclick="updatePaymentStatus(${payment.id}, ${orderId}, 'Accepted')">Accept Payment</button>
                                            <button class="btn btn-sm bg-red-500 ml-4" onclick="openDeclineReasonModal(${payment.id}, ${orderId})">Decline Payment</button>
                                        </div>

                                        <dialog id="decline_reason_modal" class="modal">
                                            <div class="modal-box w-11/12 max-w-md">
                                                <h3 class="font-bold text-lg">Decline Payment</h3>
                                                <p class="py-4">Please provide a reason for declining this payment:</p>
                                                <input type="hidden" id="decline_payment_id">
                                                <input type="hidden" id="decline_order_id">
                                                <textarea id="decline_reason" class="textarea textarea-bordered w-full" placeholder="Enter reason..."></textarea>
                                                <div class="modal-action">
                                                    <button class="btn" onclick="submitDeclineReason()">Submit</button>
                                                    <button class="btn" onclick="closeDeclineReasonModal()">Cancel</button>
                                                </div>
                                            </div>
                                        </dialog>


                                                                        ` : ''}
                        `;
                                paymentsList.appendChild(listItem);
                            });

                            // Display declined payments
                            declinedPayments.forEach((payment) => {
                                var listItem = document.createElement('div');
                                listItem.classList.add('flex', 'justify-between', 'items-center', 'border',
                                    'border-gray-200', 'rounded-md', 'p-4', 'mb-2', 'bg-white', 'shadow-sm',
                                    'flex-col', 'lg:flex-row');

                                // Determine the payment label for declined payments
                                var paymentLabel = `Declined Payment`;

                                // Status color is always red for declined payments
                                var statusColorClass = 'text-red-600';

                                // Build the innerHTML for declined payments with the reason
                                listItem.innerHTML = `
        <div class="flex-1">
            <p class="text-lg font-semibold mb-2 text-gray-800">${paymentLabel}</p>
            <p class="text-md text-gray-600">Amount: ₱${Math.ceil(Number(payment.amount) * 100) / 100}</p>
            <p class="text-md text-gray-600">Date: ${new Date(payment.payment_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
            <p class="text-md text-gray-600">Status: <span class="${statusColorClass}">${payment.status}</span></p>
            <p class="text-md text-gray-600">Reason: ${payment.decline_reason ? payment.decline_reason : 'No reason provided'}</p>
        </div>
        <img src="/storage/${payment.receipt}" alt="Receipt" class="w-24 h-24 cursor-pointer rounded-md object-cover lg:w-32 lg:h-32" onclick="showImageModal(this)">
    `;
                                paymentsList.appendChild(listItem);
                            });

                            modal.showModal();
                        }
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function openDeclineReasonModal(paymentId, orderId) {
            document.getElementById('decline_payment_id').value = paymentId;
            document.getElementById('decline_order_id').value = orderId;
            document.getElementById('decline_reason_modal').showModal();
        }

        function closeDeclineReasonModal() {
            document.getElementById('decline_reason_modal').close();
        }

        function submitDeclineReason() {
            const paymentId = document.getElementById('decline_payment_id').value;
            const orderId = document.getElementById('decline_order_id').value;
            const declineReason = document.getElementById('decline_reason').value;

            if (declineReason.trim() === '') {
                alert('Please provide a reason for declining the payment.');
                return;
            }

            updatePaymentStatus(paymentId, orderId, 'Declined', declineReason);
            closeDeclineReasonModal();
        }


        // Function to determine the next payment due date based on payment progress
        function getNextPaymentDueDate(paymentsMade) {
            const today = dayjs();
            let nextDueDate = today;

            // Calculate the next due date based on the payment progress
            for (let i = 0; i <= paymentsMade; i++) {
                if (nextDueDate.date() <= 15) {
                    nextDueDate = nextDueDate.endOf('month'); // Next payment at the end of the current month
                } else {
                    nextDueDate = nextDueDate.add(1, 'month').date(15); // 15th of the next month
                }
            }

            return nextDueDate;
        }




        function showImageModal(element) {
            var displayDiv = document.getElementById('imageDisplay');
            var displayImg = document.getElementById('displayImage');
            displayImg.src = element.src;
            displayDiv.classList.remove('hidden');
            displayDiv.classList.add('flex');
        }

        function closeImageDisplay() {
            var displayDiv = document.getElementById('imageDisplay');
            displayDiv.classList.add('hidden');
            displayDiv.classList.remove('flex');
        }

        document.getElementById('imageDisplay').addEventListener('click', function(event) {
            if (event.target === this) {
                closeImageDisplay();
            }
        });



        function updatePaymentStatus(paymentId, orderId, status, declineReason = null) {
            fetch(`/admin/update-payment-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        payment_id: paymentId,
                        status: status,
                        decline_reason: declineReason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        viewLayawayDetails(orderId); // Refresh the layaway details
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
