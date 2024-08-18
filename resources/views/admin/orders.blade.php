@extends('layouts.adminlayout')

@section('content')
    <div class="container mx-auto mt-10 px-4">
        <!-- Orders Info Collapse for Mobile Devices -->
        <div class="block md:hidden mb-6">
            <div class="collapse bg-white shadow-md rounded-lg">
                <input type="checkbox" />
                <div class="collapse-title text-xl font-medium text-center">Orders Info</div>
                <div class="collapse-content">
                    <div class="bg-amber-100 text-amber-800 p-4 rounded-lg shadow-md mb-4">
                        <h2 class="text-xl font-bold">New Orders</h2>
                        <p class="text-2xl">{{ $newOrdersCount }}</p>
                        <p class="text-sm">{{ $newOrdersImpression }}%</p>
                    </div>
                    <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow-md mb-4">
                        <h2 class="text-xl font-bold">Shipped Orders</h2>
                        <p class="text-2xl">{{ $pendingOrdersCount }}</p>
                        <p class="text-sm">{{ $pendingOrdersImpression }}%</p>
                    </div>
                    <div class="bg-emerald-100 text-emerald-800 p-4 rounded-lg shadow-md mb-4">
                        <h2 class="text-xl font-bold">Delivered Orders</h2>
                        <p class="text-2xl">{{ $deliveredOrdersCount }}</p>
                        <p class="text-sm">{{ $deliveredOrdersImpression }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Info for Larger Devices -->
        <div class="hidden md:flex flex-wrap justify-between mb-6">
            <div class="bg-amber-100 text-amber-800 p-4 rounded-lg shadow-md flex-1 mx-2">
                <h2 class="text-xl font-bold">New Orders</h2>
                <p class="text-2xl">{{ $newOrdersCount }}</p>
                <p class="text-sm">{{ $newOrdersImpression }}%</p>
            </div>
            <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow-md flex-1 mx-2">
                <h2 class="text-xl font-bold">Shipped Orders</h2>
                <p class="text-2xl">{{ $pendingOrdersCount }}</p>
                <p class="text-sm">{{ $pendingOrdersImpression }}%</p>
            </div>
            <div class="bg-emerald-100 text-emerald-800 p-4 rounded-lg shadow-md flex-1 mx-2">
                <h2 class="text-xl font-bold">Delivered Orders</h2>
                <p class="text-2xl">{{ $deliveredOrdersCount }}</p>
                <p class="text-sm">{{ $deliveredOrdersImpression }}%</p>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="overflow-x-auto py-2 mb-4 flex justify-start md:justify-center space-x-2 md:space-x-4">
            <button
                class="filter-btn active px-4 py-2 bg-blue-600 text-white rounded-full shadow-md transition duration-300"
                onclick="filterOrders('all')">All Orders</button>
            <button
                class="filter-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-full shadow-md hover:bg-blue-100 transition duration-300"
                onclick="filterOrders('preparing')">New Orders</button>
            <button
                class="filter-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-full shadow-md hover:bg-blue-100 transition duration-300"
                onclick="filterOrders('shipped')">Shipped Orders</button>
            <button
                class="filter-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-full shadow-md hover:bg-blue-100 transition duration-300"
                onclick="filterOrders('delivered')">Delivered Orders</button>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 xl:hidden" id="mobileOrderList">
            @isset($orders)
                @foreach ($orders as $order)
                    <div
                        class="bg-white border border-gray-200 rounded-lg shadow-md mb-4 p-4 transition duration-300 hover:shadow-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h2 class="text-lg font-bold">Order #{{ $order->id }}</h2>
                            <button
                                class="bg-blue-500 text-white px-3 py-1 rounded-full shadow-md transition duration-300 hover:bg-blue-600"
                                onclick="openOrderInfo({{ $order->id }})">Info</button>

                        </div>
                        <p><strong>Customer:</strong> {{ $order->customer->name }}</p>
                        <p><strong>Date:</strong> {{ date('F j, Y', strtotime($order->order_date)) }}</p>
                        <p><strong>Total:</strong> ₱{{ number_format($order->total_amount, 0, '.', ',') }}</p>
                        <p><strong>Status:</strong> <span
                                class="{{ $order->shipping_status == 'delivered' ? 'text-emerald-600' : ($order->shipping_status == 'shipped' ? 'text-blue-600' : ($order->shipping_status == 'preparing' ? 'text-amber-600' : '')) }}">{{ ucfirst($order->shipping_status) }}</span>
                        </p>
                    </div>
                @endforeach
            @else
                <p class="text-center">No orders found.</p>
            @endisset
        </div>

        <dialog id="orderInfoModal" class="modal">
            <div class="modal-box w-full h-full max-w-full max-h-screen overflow-y-auto p-4 rounded-none">
                <form method="dialog">
                    <button id="closeOrderInfoModal"
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="text-3xl font-bold mb-4 text-center">Order Details</h3>
                <div id="orderDetails">
                    <!-- Order details will be populated here dynamically -->
                </div>
            </div>
        </dialog>





        <!-- Orders Table for Larger Devices -->
        <div class="overflow-x-auto hidden xl:block">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-gray-100 text-gray-800">
                    <tr class="text-lg">
                        <th class="px-4 py-2 text-center text-sm font-semibold">Order Number</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold">Customer Name</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold">Order Date</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold">Shipping Address</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold">Total Amount</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold">Number of Items</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold">Shipping Procedure</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold">Shipping Status</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold">Contact</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold">Receipt</th>
                    </tr>
                </thead>
                <tbody id="orderTableBody" class="text-gray-700">
                    @isset($orders)
                        @foreach ($orders as $order)
                            @php
                                $shippingAddress = json_decode($order->shipping_address, true);
                                $addressComponents = [];

                                if (isset($shippingAddress['street_address'])) {
                                    $addressComponents[] = $shippingAddress['street_address'];
                                } elseif (isset($shippingAddress['street_address_other'])) {
                                    $addressComponents[] = $shippingAddress['street_address_other'];
                                }

                                if (isset($shippingAddress['city'])) {
                                    $addressComponents[] = $shippingAddress['city'];
                                } elseif (isset($shippingAddress['city_other'])) {
                                    $addressComponents[] = $shippingAddress['city_other'];
                                }

                                if (isset($shippingAddress['province'])) {
                                    $addressComponents[] = $shippingAddress['province'];
                                } elseif (isset($shippingAddress['province_other'])) {
                                    $addressComponents[] = $shippingAddress['province_other'];
                                }

                                if (isset($shippingAddress['postal_code'])) {
                                    $addressComponents[] = $shippingAddress['postal_code'];
                                } elseif (isset($shippingAddress['postal_code_other'])) {
                                    $addressComponents[] = $shippingAddress['postal_code_other'];
                                }

                                $formattedAddress = implode(', ', $addressComponents);
                            @endphp
                            <tr class="border-b hover:bg-gray-50 transition duration-300">
                                <td class="px-4 py-2 text-center">{{ $order->id }}</td>
                                <td class="px-4 py-2 text-center">{{ $order->customer->name }}</td>
                                <td class="px-4 py-2 text-center">{{ date('F j, Y', strtotime($order->order_date)) }}</td>
                                <td class="px-4 py-2 text-center">{{ $formattedAddress }}</td>
                                <td class="px-4 py-2 text-center">₱{{ number_format($order->total_amount, 0, '.', ',') }}</td>
                                <td class="px-4 py-2 text-center text-gray-900 text-md">
                                    <button
                                        class="shadow-md border-gray-200 border-2 rounded-full px-3 py-1 transition duration-300 hover:bg-gray-100"
                                        onclick="getItems({{ $order->id }})">{{ $order->num_orders }}</button>
                                </td>
                                <td class="px-4 py-2 text-center">{{ $order->shipping_procedure }}</td>
                                <td
                                    class="px-4 py-2 text-center {{ $order->shipping_status == 'delivered' ? 'text-emerald-600' : ($order->shipping_status == 'shipped' ? 'text-blue-600' : ($order->shipping_status == 'preparing' ? 'text-amber-600' : '')) }}">
                                    <button onclick="getStatus({{ $order->id }})">Edit</button>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    {{ $order->customer->email }}<br>{{ $order->customer->phone_number }}</td>
                                <td class="px-4 py-2 text-center">
                                    <img src="{{ asset('storage/' . $order->receipt) }}" alt="Receipt"
                                        class="h-24 w-auto cursor-pointer"
                                        onclick="openReceiptModal('{{ asset('storage/' . $order->receipt) }}')">
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="px-4 py-2 text-center">No orders found.</td>
                        </tr>
                    @endisset
                </tbody>
            </table>
        </div>
    </div>

    <dialog id="my_modal_3" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button id="closeModalButton" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg">Items</h3>
            <ul id="itemList" class="py-4"></ul>
        </div>
    </dialog>

    <dialog id="status_modal" class="modal">
        <div class="modal-box">
            <form id="updateOrderForm" method="dialog">
                <button id="closeStatusModalButton"
                    class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                <h3 class="font-bold text-2xl text-center">Order Status</h3>
                <div id="orderDetails" class="py-4">
                    <div class="mb-4">
                        <x-input-label for="shippingMethod" :value="__('Shipping Method')" />
                        <x-text-input id="shippingMethod" name="shipping_method" type="text"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        <x-input-error class="mt-2" :messages="$errors->get('shipping_method')" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="trackingNumber" :value="__('Tracking Number')" />
                        <x-text-input id="trackingNumber" name="tracking_number" type="text"
                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        <x-input-error class="mt-2" :messages="$errors->get('tracking_number')" />
                    </div>
                    <div class="mb-4">
                        <x-input-label for="shippingStatus" :value="__('Shipping Status')" />
                        <select id="shippingStatus" name="shipping_status"
                            class="mt-1 border border-gray-300 rounded-md py-2 px-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-opacity-50 w-full">
                            <option value="preparing">Preparing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="declined">Declined</option>
                        </select>

                        <x-input-error class="mt-2" :messages="$errors->get('shipping_status')" />
                    </div>
                    <button type="submit"
                        class="btn btn-primary w-full text-slate-900 bg-green border-none hover:bg-emerald-700 mt-3">Update</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog id="receipt_modal" class="modal">
        <div class="modal-box max-w-5xl">
            <form method="dialog">
                <button id="closeReceiptModalButton"
                    class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <div class="py-4 text-center flex justify-center items-center">
                <img id="modalReceiptImage" src="" alt="Receipt" class="max-w-full h-auto">
            </div>
        </div>
    </dialog>

    <script>
        function filterOrders(status) {
            fetch(`{{ url('/filter-orders/') }}/${status}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const orderTableBody = document.getElementById('orderTableBody');
                    const mobileOrderList = document.getElementById('mobileOrderList');
                    orderTableBody.innerHTML = '';
                    mobileOrderList.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(order => {
                            const shippingAddress = JSON.parse(order.shipping_address);
                            const addressComponents = [];

                            if (shippingAddress.street_address) {
                                addressComponents.push(shippingAddress.street_address);
                            } else if (shippingAddress.street_address_other) {
                                addressComponents.push(shippingAddress.street_address_other);
                            }

                            if (shippingAddress.city) {
                                addressComponents.push(shippingAddress.city);
                            } else if (shippingAddress.city_other) {
                                addressComponents.push(shippingAddress.city_other);
                            }

                            if (shippingAddress.province) {
                                addressComponents.push(shippingAddress.province);
                            } else if (shippingAddress.province_other) {
                                addressComponents.push(shippingAddress.province_other);
                            }

                            if (shippingAddress.postal_code) {
                                addressComponents.push(shippingAddress.postal_code);
                            } else if (shippingAddress.postal_code_other) {
                                addressComponents.push(shippingAddress.postal_code_other);
                            }

                            const formattedAddress = addressComponents.join(', ');

                            // Format date
                            const options = {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            };
                            const formattedDate = new Date(order.order_date).toLocaleDateString('en-US',
                                options);

                            // Format total amount
                            const formattedAmount = `₱${parseInt(order.total_amount).toLocaleString()}`;

                            // For table
                            orderTableBody.innerHTML += `
                            <tr class="border-b hover:bg-gray-50 transition duration-300">
                                <td class="px-4 py-2 text-center">${order.id}</td>
                                <td class="px-4 py-2 text-center">${order.customer.name}</td>
                                <td class="px-4 py-2 text-center">${formattedDate}</td>
                                <td class="px-4 py-2 text-center">${formattedAddress}</td>
                                <td class="px-4 py-2 text-center">${formattedAmount}</td>
                                <td class="px-4 py-2 text-center text-gray-900 text-md">
                                    <button class="shadow-md border-gray-200 border-2 rounded-full px-3 py-1 transition duration-300 hover:bg-gray-100"
                                        onclick="getItems(${order.id})">${order.num_orders}</button>
                                </td>
                                <td class="px-4 py-2 text-center">${order.shipping_procedure}</td>
                                <td class="px-4 py-2 text-center ${order.shipping_status == 'delivered' ? 'text-emerald-600' : (order.shipping_status == 'shipped' ? 'text-blue-600' : (order.shipping_status == 'preparing' ? 'text-amber-600' : ''))}">
                                    <button onclick="getStatus(${order.id})">Edit</button>
                                </td>
                                <td class="px-4 py-2 text-center">${order.customer.email}<br>${order.customer.phone_number}</td>
                                <td class="px-4 py-2 text-center">
                                    <img src="{{ asset('storage/') }}/${order.receipt}" alt="Receipt" class="h-24 w-auto cursor-pointer"
                                        onclick="openReceiptModal('{{ asset('storage/') }}/${order.receipt}')">
                                </td>
                            </tr>
                            `;

                            // For mobile divs
                            mobileOrderList.innerHTML += `
                            <div class="bg-white border border-gray-200 rounded-lg shadow-md mb-4 p-4 transition duration-300 hover:shadow-lg">
                                <div class="flex justify-between items-center mb-2">
                                    <h2 class="text-lg font-bold">Order #${order.id}</h2>
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded-full shadow-md transition duration-300 hover:bg-blue-600"
                                        onclick="openOrderInfo(${order.id})">Info</button>
                                </div>
                                <p><strong>Customer:</strong> ${order.customer.name}</p>
                                <p><strong>Date:</strong> ${formattedDate}</p>
                                <p><strong>Total:</strong> ${formattedAmount}</p>
                                <p><strong>Status:</strong> <span class="${order.shipping_status == 'delivered' ? 'text-emerald-600' : (order.shipping_status == 'shipped' ? 'text-blue-600' : (order.shipping_status == 'preparing' ? 'text-amber-600' : ''))}">${order.shipping_status.charAt(0).toUpperCase() + order.shipping_status.slice(1)}</span></p>
                            </div>
                            `;
                        });
                    } else {
                        orderTableBody.innerHTML = `
                        <tr>
                            <td colspan="10" class="px-4 py-2 text-center">No orders found.</td>
                        </tr>
                        `;
                        mobileOrderList.innerHTML = `
                        <p class="text-center">No orders found.</p>
                        `;
                    }

                    // Update active button
                    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active',
                        'bg-blue-600', 'text-white'));
                    document.querySelector(`.filter-btn[onclick="filterOrders('${status}')"]`).classList.add('active',
                        'bg-blue-600', 'text-white');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function getItems(orderId) {
            fetch('{{ url('/get-orders/') }}/' + orderId, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modal = document.getElementById('my_modal_3');
                        if (modal) {
                            modal.showModal();
                            populateItemList(data.items);
                        }
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function populateItemList(items) {
            var itemList = document.getElementById('itemList');
            if (itemList) {
                itemList.innerHTML = '';

                items.forEach(item => {
                    var listItem = document.createElement('li');
                    listItem.classList.add('border', 'border-gray-300', 'rounded-md', 'p-4', 'mb-4', 'flex',
                        'items-center');

                    var detailsContainer = document.createElement('div');
                    detailsContainer.classList.add('flex-1');

                    detailsContainer.innerHTML = `
                        <p class="text-lg font-semibold mb-2">${item.product_name}</p>
                        <p><strong>Brand:</strong> ${item.product_brand}</p>
                        <p><strong>Size:</strong> ${item.size}</p>
                        <p><strong>Color:</strong> ${item.color}</p>
                        <p><strong>Quantity:</strong> ${item.quantity}</p>
                    `;

                    listItem.appendChild(detailsContainer);

                    var imageContainer = document.createElement('div');
                    imageContainer.classList.add('ml-4');

                    var image = document.createElement('img');
                    image.src = "{{ asset('storage/') }}/" + item.image_paths[0];
                    image.alt = "Image";
                    image.classList.add('h-[250px]', 'w-auto', 'rounded-md');

                    imageContainer.appendChild(image);
                    listItem.appendChild(imageContainer);
                    itemList.appendChild(listItem);
                });
            }
        }

        function getStatus(orderId) {
            fetch('{{ url('/get-status/') }}/' + orderId, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modal = document.getElementById('status_modal');
                        if (modal) {
                            modal.showModal();
                            modal.dataset.orderId = orderId;
                            document.getElementById('shippingMethod').value = data.shipping_method;
                            document.getElementById('trackingNumber').value = data.tracking_number;
                            document.getElementById('shippingStatus').value = data.shipping_status;
                        }
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function openReceiptModal(imageSrc) {
            var modal = document.getElementById('receipt_modal');
            var modalImage = document.getElementById('modalReceiptImage');
            modalImage.src = imageSrc;
            modal.showModal();
        }

        document.getElementById('closeReceiptModalButton').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('receipt_modal').close();
        });

        document.getElementById('updateOrderForm').addEventListener('submit', function(event) {
            event.preventDefault();
            updateOrder();
        });

        function updateOrder() {
            const orderId = document.getElementById('status_modal').dataset.orderId;
            const shippingMethod = document.getElementById('shippingMethod').value;
            const trackingNumber = document.getElementById('trackingNumber').value;
            const shippingStatus = document.getElementById('shippingStatus').value;

            fetch('{{ url('/update-status/') }}/' + orderId, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        shipping_method: shippingMethod,
                        tracking_number: trackingNumber,
                        shipping_status: shippingStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('status_modal').close();
                        location.reload();
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function openOrderInfo(orderId) {
            fetch(`{{ url('/orders/') }}/${orderId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const order = data.order;
                        const items = data.items;

                        const shippingAddress = JSON.parse(order.shipping_address);
                        const addressComponents = [];

                        if (shippingAddress.street_address) {
                            addressComponents.push(shippingAddress.street_address);
                        } else if (shippingAddress.street_address_other) {
                            addressComponents.push(shippingAddress.street_address_other);
                        }

                        if (shippingAddress.city) {
                            addressComponents.push(shippingAddress.city);
                        } else if (shippingAddress.city_other) {
                            addressComponents.push(shippingAddress.city_other);
                        }

                        if (shippingAddress.province) {
                            addressComponents.push(shippingAddress.province);
                        } else if (shippingAddress.province_other) {
                            addressComponents.push(shippingAddress.province_other);
                        }

                        if (shippingAddress.postal_code) {
                            addressComponents.push(shippingAddress.postal_code);
                        } else if (shippingAddress.postal_code_other) {
                            addressComponents.push(shippingAddress.postal_code_other);
                        }

                        const formattedAddress = addressComponents.join(', ');
                        const formattedDate = new Date(order.order_date).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        const formattedAmount = `₱${parseInt(order.total_amount).toLocaleString()}`;

                        const itemDetails = items.map(item => {
                            const imageUrl = item.image_paths.length ?
                                `{{ asset('storage/') }}/${item.image_paths[0]}` : '';
                            return `
                    <div class="flex-shrink-0 w-64 p-2 border border-gray-200 rounded-lg shadow-sm mr-2">
                        <p class="font-semibold text-gray-800">${item.product_name}</p>
                        <p class="text-gray-600">${item.product_brand}</p>
                        <p class="text-gray-600">Size: ${item.size}</p>
                        <p class="text-gray-600">Color: ${item.color}</p>
                        <p class="text-gray-600">Quantity: ${item.quantity}</p>
                        ${imageUrl ? `<img src="${imageUrl}" alt="Product Image" class="h-24 w-auto mt-2">` : ''}
                    </div>
                    `;
                        }).join('');

                        document.getElementById('orderDetails').innerHTML = `
                    <div class="space-y-4 text-sm md:text-base text-gray-700">
                        <p><strong class="text-gray-800">Order Number:</strong> <span class="font-medium">${order.id}</span></p>
                        <p><strong class="text-gray-800">Customer:</strong> <span class="font-medium">${order.customer.name}</span></p>
                        <p><strong class="text-gray-800">Date:</strong> <span class="font-medium">${formattedDate}</span></p>
                        <p><strong class="text-gray-800">Address:</strong> <span class="font-medium">${formattedAddress}</span></p>
                        <p><strong class="text-gray-800">Total:</strong> <span class="font-medium">${formattedAmount}</span></p>
                        <p><strong class="text-gray-800">Shipping Procedure:</strong> <span class="font-medium">${order.shipping_procedure}</span></p>
                        <p><strong class="text-gray-800">Contact:</strong> <span class="font-medium">${order.customer.email} / ${order.customer.phone_number}</span></p>
                        <p><strong class="text-gray-800">Receipt:</strong></p>
                        <img src="{{ asset('storage/') }}/${order.receipt}" alt="Receipt" class="h-24 w-auto cursor-pointer mt-2" onclick="openReceiptModal('{{ asset('storage/') }}/${order.receipt}')">
                        <p><strong class="text-gray-800">Number of Items:</strong> <span class="font-medium">${items.length}</span></p>
                    </div>
                    <div class="border-t border-gray-300 my-4"></div>
                    <strong class="text-gray-800 block mb-2">Items Ordered:</strong>
                    <div class="flex overflow-x-auto space-x-4 p-2">
                        ${itemDetails}
                    </div>
                    <div class="border-t border-gray-300 my-4"></div>
                    <div class="mb-4">
                        <label for="shippingMethod" class="block text-sm font-medium text-gray-700">Shipping Method</label>
                        <input type="text" id="shippingMethod" name="shipping_method" value="${order.shipping_method}" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                    </div>
                    <div class="mb-4">
                        <label for="trackingNumber" class="block text-sm font-medium text-gray-700">Tracking Number</label>
                        <input type="text" id="trackingNumber" name="tracking_number" value="${order.tracking_number}" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                    </div>
                    <div class="mb-4">
    <label for="shippingStatus" class="block text-sm font-medium text-gray-700">Shipping Status</label>
    <select id="shippingStatus" name="shipping_status" class="mt-1 p-2 border border-gray-300 rounded-md w-full">
        <option value="preparing" ${order.shipping_status === 'preparing' ? 'selected' : ''}>Preparing</option>
        <option value="shipped" ${order.shipping_status === 'shipped' ? 'selected' : ''}>Shipped</option>
        <option value="delivered" ${order.shipping_status === 'delivered' ? 'selected' : ''}>Delivered</option>
        <option value="declined" ${order.shipping_status === 'declined' ? 'selected' : ''}>Declined</option>
    </select>
</div>

                    <button type="button" onclick="updateOrder()" class="btn btn-primary w-full mt-4">Save</button>
                `;

                        // Set order ID to the status_modal for updateOrder function to work correctly
                        document.getElementById('status_modal').dataset.orderId = order.id;

                        document.getElementById('orderInfoModal').showModal();
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        document.getElementById('closeOrderInfoModal').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('orderInfoModal').close();
        });

        function openReceiptModal(imageSrc) {
            var modal = document.getElementById('receipt_modal');
            var modalImage = document.getElementById('modalReceiptImage');
            modalImage.src = imageSrc;
            modal.showModal();
        }
    </script>
@endsection
