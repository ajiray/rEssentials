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
                class="fixed top-28 left-0 w-[40%] right-0 mx-auto z-50 text-center text-xl py-3 px-4 bg-green text-slate-800 rounded-lg shadow-md">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        <div class="mb-4 mt-10">
            <!-- Add Product Button -->
            <button class="btn btn-primary text-slate-900 border-none hover:bg-emerald-700 bg-green"
                onclick="my_modal_3.showModal()">Add Item</button>
        </div>

        <dialog id="my_modal_3" class="modal">
            <div class="modal-box">
                <form method="POST" action="{{ route('products.store') }}">
                    @csrf
                    <button id="closeModalButton" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                    <h3 class="font-bold text-xl text-gray-700 text-center mb-5" id="modalHeder">Add New Item</h3>

                    <!-- Input fields -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" class="input input-bordered"
                                placeholder="Enter name" required>
                        </div>
                        <div>
                            <label for="brand">Brand:</label>
                            <input type="text" id="brand" name="brand" class="input input-bordered"
                                placeholder="Enter brand" required>
                        </div>
                        <div>
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" class="textarea textarea-bordered" placeholder="Enter description"></textarea>
                        </div>
                        <div>
                            <label for="category">Category:</label>
                            <input list="categoryList" id="category" name="category" class="input input-bordered"
                                placeholder="Enter or select category" required>
                            <datalist id="categoryList">
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <!-- Add Item button -->
                    <div class="text-right mt-4">
                        <button type="submit"
                            class="btn btn-primary text-slate-900 border-none hover:bg-emerald-700 bg-green w-full">Add
                            Item</button>
                    </div>
                </form>
            </div>
        </dialog>



        @isset($item)
            <dialog id="my_modal_2" class="modal">
                <div class="modal-box">
                    <form method="POST" action="{{ route('products.update', $item->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <button id="closeModalButton2" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                        <h3 class="font-bold text-xl text-gray-700 text-center mb-5" id="modalHeader">Edit Item</h3>

                        <!-- Input fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" class="input input-bordered"
                                    placeholder="Enter name" value="{{ $item->product->name }}" required>
                            </div>
                            <div>
                                <label for="brand">Brand:</label>
                                <input type="text" id="brand" name="brand" class="input input-bordered"
                                    placeholder="Enter brand" value="{{ $item->product->brand }}" required>
                            </div>
                            <div>
                                <label for="color">Color:</label>
                                <input type="text" id="color" name="color" class="input input-bordered"
                                    placeholder="Enter color" value="{{ $item->color }}" required>
                            </div>
                            <div>
                                <label for="size">Size:</label>
                                <input type="text" id="size" name="size" class="input input-bordered"
                                    placeholder="Enter size" value="{{ $item->size }}" required>
                            </div>
                            <div>
                                <label for="description">Description:</label>
                                <textarea id="description" value="{{ $item->description }}" name="description" class="textarea textarea-bordered"
                                    placeholder="Enter description">{{ $item->product->description }}</textarea>
                            </div>
                            <div>
                                <label for="price">Price:</label>
                                <input type="number" id="price" name="price" class="input input-bordered"
                                    placeholder="Enter price" value="{{ $item->price }}" required>
                            </div>

                        </div>

                        <!-- Edit Item button -->
                        <div class="text-right mt-4">
                            <button type="submit"
                                class="btn btn-primary text-slate-900 border-none hover:bg-emerald-700 bg-green w-full">Edit
                                Item</button>
                        </div>
                    </form>

                </div>
            </dialog>
            <script>
                // Open the modal
                my_modal_2.showModal();

                document.getElementById('closeModalButton2').addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the default behavior of the button click event
                    document.getElementById('my_modal_2').close(); // Close the modal
                    window.location.href =
                        '{{ route('product') }}';
                });
            </script>
        @endisset



        @isset($images)
            <dialog id="my_modal_1" class="modal">
                <div class="modal-box">

                    <button id="closeModalButton3" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

                    <h3 class="font-bold text-xl text-gray-700 text-center mb-5">{{ $product->name }}</h3>

                    <!-- Display all images associated with the product -->
                    <div class="grid grid-cols-3 gap-4">
                        @foreach ($images as $image)
                            <div class="relative h-40 w-40">
                                <!-- Image -->
                                <img src="{{ asset('storage/' . $image->path) }}" alt="Image"
                                    class="object-cover w-full h-full">

                                <!-- Delete button (X mark) -->
                                <form method="POST" action="{{ route('products.deleteImage', $image->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-gray-700 hover:bg-gray-200 rounded-full p-1 focus:outline-none absolute -top-2 -right-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('products.addImage') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Input field for multiple images -->
                        <div class="mt-4">
                            <input type="hidden" name="variant_id" value="{{ $variant_id }}">
                            <label for="new_images" class="block font-bold">Add New Images:</label>
                            <input type="file" id="new_images" name="new_images[]"
                                class="mt-2 mb-2 file-input file-input-bordered w-full" accept="image/*" multiple required>
                        </div>

                        <!-- Add button to submit new images -->
                        <div class="text-right mt-4 w-full flex justify-center items-center">
                            <button type="submit"
                                class="btn btn-primary text-slate-900 border-none hover:bg-emerald-700 bg-green w-[30%]">Add</button>
                        </div>
                    </form>
                </div>

            </dialog>
            <script>
                // Open the modal
                my_modal_1.showModal();

                document.getElementById('closeModalButton3').addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the default behavior of the button click event
                    document.getElementById('my_modal_1').close(); // Close the modal

                    // Redirect to a specific route
                    window.location.href = '/admin/products';
                });
            </script>
        @endisset




        <!-- Add Variant Modal -->
        <dialog id="addVariantModal" class="modal">
            <div class="modal-box">
                <form method="POST" action="{{ route('products.addvariant') }}" enctype="multipart/form-data">
                    @csrf
                    <button id="closeModalButton4"
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    <input type="hidden" name="product_id" id="product_id">
                    <!-- Add inputs for variant attributes -->
                    <h3 class="font-bold text-xl text-gray-700 text-center mb-5">Add Variant</h3>
                    <!-- Variant Attributes -->
                    <div class="flex flex-col space-y-3">

                        <x-input-label for="color" :value="__('Color')" />
                        <x-text-input id="color" name="color" placeholder="Enter color" required />

                        <x-input-label for="size" :value="__('Size')" />
                        <x-text-input id="size" name="size" placeholder="Enter size" required />

                        <x-input-label for="price" :value="__('Price')" />
                        <x-text-input id="price" name="price" placeholder="Enter price" required type="number" />

                        <div>
                            <label for="new_images" class="block font-bold">Add Images:</label>
                            <input type="file" id="images" name="images[]"
                                class="mt-2 mb-2 file-input file-input-bordered w-full" accept="image/*" multiple
                                required>
                            <!-- Div to display file names -->
                            <div id="file-names" class="text-sm text-gray-700"></div>
                        </div>
                    </div>



                    <!-- Add more inputs for other variant attributes as needed -->
                    <button type="submit" class="btn btn-primary w-full mt-5">Add Variant</button>

                </form>
            </div>
        </dialog>

        <script>
            function openAddVariantModal(productId) {
                document.getElementById('product_id').value = productId;
                document.getElementById('addVariantModal').showModal();
            }

            document.getElementById('closeModalButton4').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default behavior of the button click event
                document.getElementById('addVariantModal').close(); // Close the modal

                // Redirect to a specific route
                window.location.href = '/admin/products';
            });
        </script>





        <!-- Display Products -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-800">
                    <tr>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Name</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Image</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Brand</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Color</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Size</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Price</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Description</th>
                        <th class="py-3 px-4 text-center text-sm font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @isset($products)
                        @foreach ($products as $product)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 text-center">{{ $product->name }}</td>
                                <td class="py-3 px-4 text-center">
                                    <a id="show_{{ $product->id }}" href="#"
                                        class="text-green hover:text-emerald-600">View</a>
                                </td>
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
                                        <select name="color" id="color_{{ $product->id }}"
                                            onchange="populatePrices(this)" class="border rounded px-2 py-1">
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
                                <td class="py-3 px-4 text-center">{{ $product->description }}</td>
                                <td class="py-3 px-4 text-center space-y-2">
                                    <a id="edit_{{ $product->id }}" href="#"
                                        class="inline-block px-3 py-1 bg-yellow border-none text-slate-900 hover:bg-amber-500 rounded">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-block px-3 py-1 bg-delete text-gray-100 hover:bg-red-700 rounded"
                                            onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                    </form>
                                    <button
                                        class="inline-block px-3 py-1 bg-green text-slate-900 border-none hover:bg-emerald-500 rounded"
                                        onclick="openAddVariantModal('{{ $product->id }}')">Add Variant</button>
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>


    </div>



    <script>
        function updateFileNames() {
            var fileNamesDiv = document.getElementById('file-names');
            var files = document.getElementById('images').files;
            var fileNames = [];

            if (files.length > 0) {
                // Loop through each selected file
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];

                    // Store the file name in the fileNames array
                    fileNames.push(file.name);
                }
            }

            // Display the file names in the file names div
            fileNamesDiv.textContent = fileNames.join(', ');
        }

        // Call the updateFileNames function whenever the input field changes
        document.getElementById('images').addEventListener('change', updateFileNames);

        // Call the updateFileNames function initially to display file names if any files were pre-selected
        updateFileNames();

        document.getElementById('closeModalButton').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default behavior of the button click event
            document.getElementById('my_modal_3').close(); // Close the modal
        });

        function populateSizesAndPrices(colorDropdown) {
            var productId = colorDropdown.id.split('_')[1];
            var selectedColor = colorDropdown.value;
            var sizeDropdown = document.getElementById('size_' + productId);
            var priceSpan = document.getElementById('price_' + productId);
            var quantitySpan = document.getElementById('quantity_' + productId);
            var edit = document.getElementById('edit_' + productId); // Use product ID to select the edit link
            var show = document.getElementById('show_' + productId); // Use product ID to select the show link

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
            }

            // Update add stock link with the first variant ID
            if (variants.length > 0) {
                edit.href = "{{ route('products.edit', ['id' => ':id', 'variant_id' => ':variant_id']) }}"
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
            var edit = document.getElementById('edit_' + productId); // Use product ID to select the edit link
            var show = document.getElementById('show_' + productId);

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
                } else {
                    priceSpan.innerText = '';

                }
                // Update add stock and mark as sold links
                edit.href = "{{ route('products.edit', ['id' => ':id', 'variant_id' => ':variant_id']) }}"
                    .replace(':id', productId)
                    .replace(':variant_id', variant.id);

                show.href = "{{ route('products.show', ['id' => ':id', 'variant_id' => ':variant_id']) }}"
                    .replace(':id', productId)
                    .replace(':variant_id', variant.id);

            } else {
                // Reset price, quantity, and links if no variant is found
                priceSpan.innerText = '';
                edit.href = '#';
                show.href = '#';

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
