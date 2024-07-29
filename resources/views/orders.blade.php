<x-app-layout>
    <dialog id="my_modal_3" class="modal">
        <div
            class="modal-box bg-white rounded-none md:rounded-xl shadow-lg p-4 w-screen h-screen max-h-screen sm:max-h-screen md:h-auto md:max-h-[800px] md:w-screen md:max-w-xl lg:max-h-[800px] lg:max-w-2xl xl:max-w-3xl mx-auto relative overflow-y-auto">
            <form method="dialog" class="absolute top-2 right-2">
                <button id="closeModalButton"
                    class="btn btn-sm btn-circle btn-ghost text-gray-500 hover:text-gray-800">✕</button>
            </form>
            <h3 class="font-bold text-lg sm:text-xl md:text-2xl lg:text-3xl text-gray-800 text-center mb-4">Items</h3>
            <ul id="itemList" class="space-y-4"></ul>
        </div>
    </dialog>








    <div class="py-12 hidden lg:block">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="p-6 sm:px-20 border-b border-marble relative">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-1 text-velvet hover:text-blue-300 absolute top-10 left-5">
                        <i class="fa-solid fa-angles-left text-xl"></i>
                        <span>Home</span>
                    </a>

                    <div class="text-5xl text-center text-velvet font-extrabold tracking-widest">
                        Your Orders
                    </div>
                </div>

                <!-- Table -->
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow-lg overflow-hidden border-b border-marble sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-500">
                                    <thead class="bg-velvet">
                                        <tr class="text-center text-blanc">
                                            <!-- Center align text in header row -->
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Order Date
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Shipping Status
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Shipping Method
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Tracking Number
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Items
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 text-center">
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                                                    <div class="text-sm font-medium tracking-wide">
                                                        {{ date('F j, Y', strtotime($order->order_date)) }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div
                                                        class="px-4 py-2 rounded-full inline-block
                                                    @if ($order->shipping_status == 'delivered') bg-emerald-100 text-emerald-600
                                                    @elseif ($order->shipping_status == 'shipped') bg-blue-100 text-blue-600
                                                    @elseif ($order->shipping_status == 'preparing') bg-amber-100 text-amber-600 @endif">
                                                        {{ $order->shipping_status }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                                                    <div class="text-sm font-medium tracking-wide">
                                                        {{ $order->shipping_method }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                                                    <div class="text-sm font-medium tracking-wide">
                                                        {{ $order->tracking_number }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <button onclick="viewItems({{ $order->id }})"
                                                        class="text-indigo-600 hover:text-indigo-800 font-semibold">View
                                                        Items</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Table -->
            </div>
        </div>
    </div>


    <!-- Mobile View -->
    <div class="lg:hidden w-full h-full flex flex-col py-6 px-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-velvet hover:text-silk mb-4">
            <i class="fa-solid fa-angles-left text-xl"></i>
            <span>Home</span>
        </a>

        <div class="text-3xl text-center text-velvet mb-6 font-bold">
            Your Orders
        </div>

        @foreach ($orders as $order)
            <div class="border border-gray-300 rounded-lg p-4 mb-4 shadow-md bg-white">
                <div class="mb-2">
                    <span class="font-semibold text-velvet">Order Date:</span>
                    <span class="text-gray-700">{{ date('F j, Y', strtotime($order->order_date)) }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-semibold text-velvet">Shipping Status:</span>
                    <span
                        class="px-2 py-1 rounded-full
                        @if ($order->shipping_status == 'delivered') bg-emerald-100 text-emerald-600
                        @elseif ($order->shipping_status == 'shipped') bg-blue-100 text-blue-600
                        @elseif ($order->shipping_status == 'preparing') bg-amber-100 text-amber-600 @endif">
                        {{ $order->shipping_status }}
                    </span>
                </div>
                <div class="mb-2">
                    <span class="font-semibold text-velvet">Shipping Method:</span>
                    <span class="text-gray-700">{{ $order->shipping_method }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-semibold text-velvet">Tracking Number:</span>
                    <span class="text-gray-700">{{ $order->tracking_number }}</span>
                </div>
                <div class="mt-4">
                    <button onclick="viewItems({{ $order->id }})"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-800 transition duration-300">
                        View Items
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function viewItems(orderId) {
            fetch(`/view-items/${orderId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Check if the request was successful
                    if (data.success) {
                        var modal = document.getElementById('my_modal_3');
                        if (modal) {
                            modal.showModal();
                            // Populate the list of items in the modal
                            populateItemList(data.items);
                        }
                    } else {
                        // Log any errors or failure messages
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    // Log any network or server errors
                    console.error('Error:', error);
                });
        }

        function populateItemList(items) {
            var itemList = document.getElementById('itemList');
            if (itemList) {
                // Clear existing items
                itemList.innerHTML = '';

                // Populate the list with item details
                items.forEach(item => {
                    var listItem = document.createElement('li');
                    listItem.classList.add('border', 'border-gray-200', 'rounded-md', 'p-4', 'mb-4', 'flex',
                        'flex-col', 'bg-white', 'shadow-sm', 'lg:flex-row');

                    // Create a container for the image
                    var imageContainer = document.createElement('div');
                    imageContainer.classList.add('w-full', 'flex', 'justify-center', 'mb-4', 'lg:w-1/3', 'lg:mb-0');

                    // Create the image element
                    var image = document.createElement('img');
                    image.src = "{{ asset('storage/') }}/" + item.image_paths[0];
                    image.alt = item.product_name;
                    image.classList.add('h-auto', 'w-full', 'max-w-[300px]', 'rounded-md');

                    // Append the image to its container
                    imageContainer.appendChild(image);

                    // Create a container for each item's details
                    var detailsContainer = document.createElement('div');
                    detailsContainer.classList.add('flex-1', 'text-center', 'lg:text-left', 'lg:pl-4');

                    // Populate item details
                    detailsContainer.innerHTML = `
                <p class="text-xl font-semibold mb-2 text-gray-800">${item.product_name}</p>
                <div class="grid grid-cols-2 gap-4 lg:grid-cols-1 lg:gap-2 text-gray-600">
                    <div><strong class="block lg:inline-block">Brand:</strong> ${item.product_brand}</div>
                    <div><strong class="block lg:inline-block">Size:</strong> ${item.size}</div>
                    <div><strong class="block lg:inline-block">Color:</strong> ${item.color}</div>
                    <div><strong class="block lg:inline-block">Quantity:</strong> ${item.quantity}</div>
                </div>
            `;

                    // Append the image container and details container to the list item
                    listItem.appendChild(imageContainer);
                    listItem.appendChild(detailsContainer);

                    // Append the list item to the item list
                    itemList.appendChild(listItem);
                });
            }
        }
    </script>
</x-app-layout>
