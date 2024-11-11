<x-app-layout>
    <style>
        html {
            scroll-behavior: smooth;
        }
    
        .no-touch-scroll {
            touch-action: none;
        }
    
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    
        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
    <div class="w-full h-screen p-10">
        <nav class="w-[90%] h-auto py-4 flex justify-between items-center mx-auto rounded-lg shadow-md bg-white">
            <div class="flex items-center space-x-2">
                <a href="{{ route('dashboard') }}" class="flex items-center text-velvet hover:text-slate-400 transition duration-300">
                    <i class="fas fa-angle-double-left text-2xl ml-5"></i>
                    <span class="ml-2 text-lg font-semibold">Home</span>
                </a>
            </div>

            <h1 class="flex-grow text-center text-2xl lg:text-5xl font-bold tracking-widest uppercase text-velvet">
                UPCOMING ITEMS
            </h1>
        </nav>

        <div id="upcoming" class="w-[90%] h-auto mx-auto mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($upcomingProducts as $product)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden p-4 hover:shadow-xl transition-shadow duration-300">
                <div class="w-full flex flex-col items-center justify-between">
                    <h2 class="text-sm sm:text-md lg:text-lg font-bold tracking-wide text-gray-800 uppercase text-center mb-4">
                        {{ $product->brand }} {{ $product->name }}
                    </h2>

                    <div class="carousel w-full h-48 flex items-center justify-center" id="carousel_{{ $product->id }}">
                        @foreach ($product->variants as $variant)
                            @foreach ($variant->images as $index => $image)
                                <div id="slide{{ $product->id }}-{{ $variant->id }}-{{ $index + 1 }}" class="carousel-item relative w-full">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }}" class="w-40 h-40 object-contain mx-auto">
                                    <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                                        @if ($index > 0)
                                            <a href="#slide{{ $product->id }}-{{ $variant->id }}-{{ $index }}" class="btn btn-circle">❮</a>
                                        @else
                                            <a class="btn btn-circle opacity-50 cursor-not-allowed">❮</a>
                                        @endif
                                        @if ($index < count($variant->images) - 1)
                                            <a href="#slide{{ $product->id }}-{{ $variant->id }}-{{ $index + 2 }}" class="btn btn-circle">❯</a>
                                        @else
                                            <a href="#slide{{ $product->id }}-{{ $variant->id }}-1" class="btn btn-circle">❯</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    <p class="mt-4 text-sm text-gray-600 text-center">
                        {{ $product->description ?? 'No description available.' }}
                    </p>

                    <p id="price_{{ $product->id }}" class="text-md font-semibold text-gray-900 mt-4">
                        ₱{{ number_format($product->variants->first()->price, 0) }}
                    </p>

                    <div class="mt-4 w-full">
                        <label for="color_{{ $product->id }}" class="block text-sm font-medium text-gray-700">Color</label>
                        <select id="color_{{ $product->id }}" name="color" class="mt-2 block w-full px-4 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md transition duration-200 ease-in-out cursor-pointer hover:border-emerald-500">
                            @php
                                $uniqueColors = $product->variants->pluck('color')->unique();
                            @endphp
                            @foreach ($uniqueColors as $color)
                                <option value="{{ $color }}">{{ $color }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4 w-full">
                        <label for="size_{{ $product->id }}" class="block text-sm font-medium text-gray-700">Size</label>
                        <select id="size_{{ $product->id }}" name="size" class="mt-2 block w-full px-4 py-2 text-sm border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md transition duration-200 ease-in-out cursor-pointer hover:border-emerald-500">
                            @php
                                $uniqueSizes = $product->variants->pluck('size')->unique();
                            @endphp
                            @foreach ($uniqueSizes as $size)
                                <option value="{{ $size }}">{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>

                    <p id="quantity_{{ $product->id }}" class="text-gray-600 mt-4">
                        Stock: {{ $product->variants->first()->quantity }}
                    </p>

                    <div class="flex items-center mt-2">
                        <button class="px-2 py-1 bg-gray-200 text-gray-800 rounded" onclick="decreaseQuantity({{ $product->id }})">-</button>
                        <input id="chosen_quantity_{{ $product->id }}" type="number" value="1" min="1" max="{{ $product->variants->first()->quantity }}" class="mx-2 w-16 text-center border rounded appearance-none" oninput="validateQuantityInput({{ $product->id }})" />
                        <button class="px-2 py-1 bg-gray-200 text-gray-800 rounded" onclick="increaseQuantity({{ $product->id }})">+</button>
                    </div>

                    <div class="mt-6 w-full">
                        <button type="button" onclick="reserveNow({{ $product->id }})"
                            class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-300">
                            Reserve Now
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>


<!-- Modal for Reserve Now -->
<div id="reserveModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <form action="{{ route('reserveItem') }}" method="POST" enctype="multipart/form-data" class="bg-white w-[90%] max-w-lg mx-auto h-[90%] rounded-lg shadow-lg overflow-hidden transform transition-all overflow-y-auto">
        @csrf
        <div class="p-6 text-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Reserve Your Item</h3>

            <!-- Hidden Inputs -->
            <input type="hidden" id="productId" name="product_id">
            <input type="hidden" id="variantId" name="variant_id">
            <input type="hidden" id="itemQuantityInput" name="quantity">

            <!-- Item Summary Section -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Item Summary</h4>
                <div class="bg-gray-100 p-4 rounded-lg shadow-sm text-left">
                    <p class="text-gray-800"><b>Product:</b> <span id="itemName">N/A</span></p>
                    <p class="text-gray-800"><b>Variant:</b> <span id="itemVariant">N/A</span></p>
                    <p class="text-gray-800"><b>Quantity:</b> <span id="itemQuantity">0</span></p>
                    <p class="text-gray-800"><b>Total Price:</b> ₱<span id="itemTotalPrice">0.00</span></p>
                </div>
            </div>

            <!-- Down Payment Section -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Down Payment (20%)</h4>
                <p id="downPaymentAmount" class="text-gray-700 font-medium text-xl">₱0.00</p>
            </div>

            <!-- Payment Methods Section -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Choose Payment Method</h4>
                <div class="flex justify-center space-x-10">
                    <div class="flex flex-col items-center">
                        <img src="{{ asset('images/gcash.png') }}" alt="GCash"
                            class="w-64 h-60 cursor-pointer transform hover:scale-105 transition-transform duration-200">
                        <label class="text-gray-800 text-sm text-center">GCash</label>
                    </div>
                    <div class="flex flex-col items-center">
                        <img src="{{ asset('images/unionbank.png') }}" alt="UnionBank"
                            class="w-64 h-60 cursor-pointer transform hover:scale-105 transition-transform duration-200">
                        <label class="text-gray-800 text-sm text-center">BDO</label>
                    </div>
                </div>
            </div>

            <!-- Receipt Upload Section -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Upload Payment Receipt</h4>
                <input type="file" id="paymentReceipt" name="payment_receipt" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm lg:text-base" required>
                <p class="text-xs text-gray-600 mt-2">Please upload a clear and legible receipt of your payment.</p>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full px-4 py-2 mt-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-300">
                Proceed
            </button>

            <!-- Cancel Button -->
            <button type="button" onclick="closeReserveModal()"
                class="w-full px-4 py-2 mt-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition ease-in-out duration-300">
                Cancel
            </button>
        </div>
    </form>
</div>
    </div>

    <script>
        const productsData = @json($upcomingProducts);
        document.addEventListener('DOMContentLoaded', function() {
            

            productsData.forEach(product => {
                const colorDropdown = document.getElementById(`color_${product.id}`);
                const sizeDropdown = document.getElementById(`size_${product.id}`);
                const priceElement = document.getElementById(`price_${product.id}`);
                const stockElement = document.getElementById(`quantity_${product.id}`);
                const quantityInput = document.getElementById(`chosen_quantity_${product.id}`);

                colorDropdown.addEventListener('change', () => {
                    const selectedColor = colorDropdown.value;
                    const availableSizes = getAvailableSizes(product.id, selectedColor);
                    populateSizesDropdown(sizeDropdown, availableSizes);
                    const firstSize = availableSizes[0];
                    updateVariant(product.id, selectedColor, firstSize);
                });

                sizeDropdown.addEventListener('change', () => {
                    const selectedSize = sizeDropdown.value;
                    updateVariant(product.id, colorDropdown.value, selectedSize);
                });

                function getAvailableSizes(productId, selectedColor) {
                    const variants = @json($upcomingProducts->pluck('variants')->flatten()->groupBy('product_id'));
                    const productVariants = variants[productId];
                    if (!productVariants) return [];
                    return productVariants.filter(variant => variant.color === selectedColor).map(variant => variant.size);
                }

                function populateSizesDropdown(sizeDropdown, sizes) {
                    sizeDropdown.innerHTML = '';
                    sizes.forEach(size => {
                        const option = document.createElement('option');
                        option.value = size;
                        option.innerText = size;
                        sizeDropdown.appendChild(option);
                    });
                }

                function updateVariant(productId, selectedColor, selectedSize) {
                    const variants = @json($upcomingProducts->pluck('variants')->flatten()->groupBy('product_id'));
                    const productVariants = variants[productId];
                    if (!productVariants) return null;

                    const variant = productVariants.find(variant => variant.color === selectedColor && variant.size === selectedSize);
                    if (variant) {
                        
                        var chosenQuantityInput = document.getElementById('chosen_quantity_' + productId);
        var currentQuantity = parseInt(chosenQuantityInput.value);
        var maxQuantity = variant.quantity;  // The available stock for this variant

        // Set the max value for the quantity input based on the variant's stock
        chosenQuantityInput.max = maxQuantity;

        // If the current quantity is greater than the max available stock, reset to 1
        if (currentQuantity > maxQuantity) {
            chosenQuantityInput.value = 1;
        } else {
            // Otherwise, keep the current quantity as it's valid
            chosenQuantityInput.value = currentQuantity;
        }
                        const formattedPrice = '₱' + variant.price.toLocaleString('en-US', { maximumFractionDigits: 0 });
                        priceElement.textContent = formattedPrice;
                        stockElement.textContent = "Stock: " + variant.quantity;
                        quantityInput.max = variant.quantity;

                        const carousel = document.getElementById(`carousel_${productId}`);
                        if (carousel) {
                            carousel.innerHTML = variant.images.map((image, index) => `
                                <div id="slide${productId}-${variant.id}-${index + 1}" class="carousel-item relative w-full">
                                    <img src="{{ asset('storage/') }}/${image.path}" alt="${variant.name}" class="w-40 h-40 object-contain mx-auto">
                                    <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                                        ${index > 0 ? `<a href="#slide${productId}-${variant.id}-${index}" class="btn btn-circle">❮</a>` : '<a class="btn btn-circle opacity-50 cursor-not-allowed">❮</a>'}
                                        ${index < variant.images.length - 1 ? `<a href="#slide${productId}-${variant.id}-${index + 2}" class="btn btn-circle">❯</a>` : '<a class="btn btn-circle opacity-50 cursor-not-allowed">❯</a>'}
                                    </div>
                                </div>
                            `).join('');
                        }
                    }
                    return variant;
                }

               
            });
        });

                function increaseQuantity(productId) {
                    const chosenQuantityInput = document.getElementById(`chosen_quantity_${productId}`);
                    const maxQuantity = parseInt(chosenQuantityInput.max);
                    const currentQuantity = parseInt(chosenQuantityInput.value);
                    if (currentQuantity < maxQuantity) {
                        chosenQuantityInput.value = currentQuantity + 1;
                    }
                }

                function decreaseQuantity(productId) {
                    const chosenQuantityInput = document.getElementById(`chosen_quantity_${productId}`);
                    const currentQuantity = parseInt(chosenQuantityInput.value);
                    if (currentQuantity > 1) {
                        chosenQuantityInput.value = currentQuantity - 1;
                    }
                }

                function validateQuantityInput(productId) {
                    const chosenQuantityInput = document.getElementById(`chosen_quantity_${productId}`);
                    const maxQuantity = parseInt(chosenQuantityInput.max);
                    const currentQuantity = parseInt(chosenQuantityInput.value);
                    if (currentQuantity > maxQuantity) {
                        chosenQuantityInput.value = maxQuantity;
                    } else if (currentQuantity < 1) {
                        chosenQuantityInput.value = 1;
                    }
                }

        let currentProductId = null;
    let currentVariantId = null;
    let currentPrice = 0;

    function reserveNow(productId) {
    const selectedColor = document.getElementById(`color_${productId}`).value;
    const selectedSize = document.getElementById(`size_${productId}`).value;
    const selectedQuantity = parseInt(document.getElementById(`chosen_quantity_${productId}`).value, 10);

    // Use the productId to find the product in the productsData array
    const product = productsData.find(p => p.id === productId);

    if (!product) {
        alert('Product not found.');
        return;
    }

    const productVariants = @json($upcomingProducts->pluck('variants')->flatten()->groupBy('product_id'))[productId];
    const selectedVariant = productVariants.find(variant => variant.color === selectedColor && variant.size === selectedSize);

    if (selectedVariant) {
        currentProductId = productId;
        currentVariantId = selectedVariant.id;
        currentPrice = parseFloat(selectedVariant.price) * selectedQuantity;

        // Populate the item summary details
        document.getElementById('itemName').textContent = `${product.brand} ${product.name}`;
        document.getElementById('itemVariant').textContent = `${selectedColor}, ${selectedSize}`;
        document.getElementById('itemQuantity').textContent = selectedQuantity;
        document.getElementById('itemTotalPrice').textContent = currentPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Set the hidden input values
        document.getElementById('productId').value = productId;
        document.getElementById('variantId').value = selectedVariant.id;
        document.getElementById('itemQuantityInput').value = selectedQuantity;

        // Calculate the down payment (20%)
        const downPayment = currentPrice * 0.2;
        document.getElementById('downPaymentAmount').textContent = `₱${downPayment.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

        // Show the modal
        document.getElementById('reserveModal').classList.remove('hidden');
    } else {
        alert('Selected variant not found. Please select a valid color and size.');
    }
}

    function closeReserveModal() {
        // Hide the modal
        document.getElementById('reserveModal').classList.add('hidden');
        // Reset the receipt input
        document.getElementById('paymentReceipt').value = '';
    }
    </script>
</x-app-layout>