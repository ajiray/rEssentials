@extends('layouts.adminlayout')

@section('content')
    <div class="w-full h-auto py-20 flex justify-center items-center">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

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
            <div class="flex justify-center items-center flex-col p-6 rounded-lg bg-red-500 hover:bg-red-600 cursor-pointer text-white transition duration-300 ease-in-out transform hover:scale-105"
                onclick="document.getElementById('inventory_report_modal').showModal()">
                <i class="fa-solid fa-warehouse text-4xl mb-2"></i>
                <p class="text-lg font-semibold">Inventory Report</p>
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
@endsection
