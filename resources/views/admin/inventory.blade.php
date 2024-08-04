@extends('layouts.adminlayout')

@section('content')
    <style>
        /* CSS for fade-out animation */
        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        .animate-fadeOut {
            animation: fadeOut 0.5s ease-out forwards;
        }
    </style>
    <div class="container mx-auto px-4">
        @if (session('success'))
            <div id="successMessage"
                class="fixed top-52 left-0 w-[40%] right-0 mx-auto z-50 text-center text-xl py-3 px-4 bg-green text-slate-800 rounded-lg shadow-md">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @isset($item)
            <dialog id="my_modal_3" class="modal">
                <div class="modal-box">
                    <form method="POST" action="{{ route('admin.addStock', [$item->id, $item->variant_id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <button id="closeModalButton" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                            type="button" onclick="document.getElementById('my_modal_3').close()">✕</button>

                        <h3 class="font-bold text-xl text-gray-700 text-center mb-5" id="modalHeader">Add Stock</h3>

                        <!-- Display item details -->
                        <div class="mb-4">
                            <p class="font-medium text-gray-700">Product Name: {{ $item->product->name }}</p>
                            <p class="font-medium text-gray-700">Brand: {{ $item->product->brand }}</p>
                            <p class="font-medium text-gray-700">Color: {{ $item->color }}</p>
                            <p class="font-medium text-gray-700">Size: {{ $item->size }}</p>
                            <p class="font-medium text-gray-700">Current Stock: {{ $item->quantity }}</p>
                        </div>

                        <!-- Input field for adding stock -->
                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" id="quantity" name="quantity" value="1"
                                class="mt-1 p-2 border rounded-md w-full" required>
                        </div>

                        <!-- Add Stock button -->
                        <div class="text-center mt-4">
                            <button type="submit"
                                class="btn btn-primary text-slate-900 border-none hover:bg-emerald-700 bg-green w-full">Add
                                Stock</button>
                        </div>
                    </form>
                </div>
            </dialog>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('my_modal_3');
                    const closeModalButton = document.getElementById('closeModalButton');

                    if (modal) {
                        modal.showModal();

                        if (closeModalButton) {
                            closeModalButton.addEventListener('click', function() {
                                modal.close(); // Close the modal
                                window.location.href = '{{ route('inventory') }}'; // Redirect to the inventory page
                            });
                        }

                    }
                });
            </script>
        @endisset

        @isset($sold)
            <dialog id="my_modal_2" class="modal">
                <div class="modal-box">
                    <form method="POST" action="{{ route('admin.sold', [$sold->product_id, $sold->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH') <!-- Use PATCH method for updating -->
                        <button id="closeModalButton2" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                            type="button" onclick="document.getElementById('my_modal_2').close()">✕</button>

                        <h3 class="font-bold text-xl text-gray-700 text-center mb-5" id="modalHeader">Mark As Sold</h3>
                        <!-- Display item details -->
                        <div class="mb-4">
                            <p class="font-medium text-gray-700">Product Name: {{ $sold->product->name }}</p>
                            <p class="font-medium text-gray-700">Brand: {{ $sold->product->brand }}</p>
                            <p class="font-medium text-gray-700">Color: {{ $sold->color }}</p>
                            <p class="font-medium text-gray-700">Size: {{ $sold->size }}</p>
                            <p class="font-medium text-gray-700">Current Stock: {{ $sold->quantity }}</p>
                        </div>

                        <!-- Input field for marking as sold -->
                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Mark As Sold</label>
                            <input type="number" id="deductQuantity" name="deductQuantity" value="1"
                                max="{{ $sold->quantity }}" min="1" class="mt-1 p-2 border rounded-md w-full" required>
                        </div>
                        <!-- Mark as Sold button -->
                        <div class="text-center mt-4">
                            <button type="submit"
                                class="btn btn-primary text-gray-100 border-none hover:bg-red-700 bg-delete w-full">Mark As
                                Sold</button>
                        </div>
                    </form>
                </div>
            </dialog>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('my_modal_2');
                    const closeModalButton = document.getElementById('closeModalButton2');

                    if (modal) {
                        modal.showModal();

                        if (closeModalButton) {
                            closeModalButton.addEventListener('click', function() {
                                modal.close(); // Close the modal
                                window.location.href =
                                    '{{ route('inventory') }}'; // Redirect to the inventory page
                            });
                        }
                    }
                });
            </script>
        @endisset


        <form method="GET" action="{{ route('inventory') }}" class="mb-3">
            <div class="relative flex justify-center items-center mt-10 space-x-2">
                <input type="text" name="search" placeholder="Search for products..." value="{{ request('search') }}"
                    class="w-[70%] py-2 px-4 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition ease-in-out duration-300">
                <button type="submit"
                    class="py-2 px-4 rounded-md shadow-sm text-base font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-300">
                    Search
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg w-[80%] mx-auto mt-10">
                <thead class="bg-gray-100 text-gray-800">
                    <tr>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Name</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Brand</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Color</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Size</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Price</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Stock</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($products as $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 text-center">{{ $product->name }}</td>
                            <td class="py-3 px-4 text-center">{{ $product->brand }}</td>
                            <td class="py-3 px-4 text-center">
                                @if ($product->variants->isNotEmpty())
                                    <select name="color" id="color_{{ $product->id }}"
                                        onchange="populateSizesAndPrices(this)" class="border rounded px-2 py-1">
                                        <option value="">Select Color</option>
                                        @foreach ($product->variants->unique('color') as $variant)
                                            <option value="{{ $variant->color }}">{{ $variant->color }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select name="color" id="color_{{ $product->id }}" onchange="populatePrices(this)"
                                        class="border rounded px-2 py-1">
                                        <option value="">Select Color</option>
                                    </select>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <select name="size" id="size_{{ $product->id }}"
                                    onchange="populatePricesAndQuantity(this)" class="border rounded px-2 py-1">
                                    <option value="">Select Size</option>
                                </select>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span id="price_{{ $product->id }}"></span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span id="quantity_{{ $product->id }}"></span>
                            </td>
                            <td class="py-3 px-4 text-center space-y-2">
                                <a id="addStockLink_{{ $product->id }}" href="#"
                                    class="inline-block px-3 py-1 bg-green text-slate-900 border-none hover:bg-emerald-500 rounded">Add
                                    Stock</a>
                                <a id="markAsSoldLink_{{ $product->id }}" href="#"
                                    class="inline-block px-3 py-1 bg-delete text-gray-100 hover:bg-red-700 rounded">Mark as
                                    Sold</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <script>
        function populateSizesAndPrices(colorDropdown) {
            var productId = colorDropdown.id.split('_')[1];
            var selectedColor = colorDropdown.value;
            var sizeDropdown = document.getElementById('size_' + productId);
            var priceSpan = document.getElementById('price_' + productId);
            var quantitySpan = document.getElementById('quantity_' + productId);
            var addStockLink = document.getElementById('addStockLink_' + productId);
            var markAsSoldLink = document.getElementById('markAsSoldLink_' + productId);

            // Find the product variants with the selected color
            var variants = @json($products)
                .find(product => product.id == productId)
                .variants
                .filter(variant => variant.color == selectedColor);

            // Populate sizes dropdown
            sizeDropdown.innerHTML = '<option value="">Select Size</option>';
            variants.forEach(variant => {
                var option = document.createElement('option');
                option.value = variant.size;
                option.innerText = variant.size;
                sizeDropdown.appendChild(option);
            });

            // Set default price and quantity if no size is selected
            if (!sizeDropdown.value) {
                priceSpan.innerText = '₱';
                quantitySpan.innerText = '';
            }

            // Update add stock link with the first variant ID
            if (variants.length > 0) {
                addStockLink.href = "{{ route('admin.updateStock', ['id' => ':id', 'variant_id' => ':variant_id']) }}"
                    .replace(':id', productId)
                    .replace(':variant_id', variants[0].id);
            }

            // Check if both color and size are selected to display the peso sign
            if (selectedColor && sizeDropdown.value) {
                priceSpan.innerText = '₱' + variants[0].price; // Assuming variants[0] is the selected variant
            } else {
                priceSpan.innerText = ''; // Reset price if color or size is not selected
            }
        }


        function populatePricesAndQuantity(sizeDropdown) {
            var productId = sizeDropdown.id.split('_')[1];
            var selectedColor = document.getElementById('color_' + productId).value;
            var selectedSize = sizeDropdown.value;
            var priceSpan = document.getElementById('price_' + productId);
            var quantitySpan = document.getElementById('quantity_' + productId);
            var addStockLink = document.getElementById('addStockLink_' + productId);
            var markAsSoldLink = document.getElementById('markAsSoldLink_' + productId);

            // Find the product variant with the selected color and size
            var variant = @json($products)
                .find(product => product.id == productId)
                .variants
                .find(variant => variant.color == selectedColor && variant.size == selectedSize);

            // Set price and quantity if a variant with the selected color and size is found
            if (variant) {
                // Check if both color and size are selected
                if (selectedColor && selectedSize) {
                    priceSpan.innerText = '₱' + variant.price;
                    quantitySpan.innerText = variant.quantity;
                } else {
                    priceSpan.innerText = '';
                    quantitySpan.innerText = '';
                }
                // Update add stock and mark as sold links
                addStockLink.href = "{{ route('admin.updateStock', ['id' => ':id', 'variant_id' => ':variant_id']) }}"
                    .replace(':id', productId)
                    .replace(':variant_id', variant.id);
                markAsSoldLink.href = "{{ route('admin.markAsSold', ['id' => ':id', 'variant_id' => ':variant_id']) }}"
                    .replace(':id', productId).replace(':variant_id', variant.id);
            } else {
                // Reset price, quantity, and links if no variant is found
                priceSpan.innerText = '';
                quantitySpan.innerText = '';
                addStockLink.href = '#';
                markAsSoldLink.href = '#';
            }
        }

        function showSuccessMessage() {
            const successMessage = document.getElementById('successMessage');
            successMessage.classList.remove('hidden');
            successMessage.classList.add('animate-fadeOut'); // Only add the fade-out animation class
            setTimeout(hideSuccessMessage, 3000); // Change to 3000 milliseconds (2 seconds)
        }

        // Check if the success message exists in the session and show it if it does
        document.addEventListener('DOMContentLoaded', function() {
            const success = '{{ session('success') }}'; // Retrieve the success message from the session
            if (success) {
                setTimeout(showSuccessMessage, 3000); // Call showSuccessMessage after 2 seconds
            }
        });
    </script>
@endsection
