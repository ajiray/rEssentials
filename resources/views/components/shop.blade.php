<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    html {
        scroll-behavior: smooth;
    }

    .no-touch-scroll {
        touch-action: none;
        /* Disables touch interaction */
    }

     /* Hide the spinners for WebKit browsers (Chrome, Safari) */
     input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Hide the spinners for Firefox */
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>

@if ($products->isEmpty())
    <div class="w-full h-screen bg-gold flex justify-center items-center flex-col">
        <h1 class="text-3xl md:text-4xl text-velvet font-bold tracking-widest xl:text-5xl text-center">
            Thank you for supporting Brhee's Clothing Boutique!
        </h1>
        <p class="text-lg md:text-2xl text-center mt-4 xl:text-2xl text-velvet">
            Our inventory is currently sold out. We appreciate your continued support.
        </p>
    </div>
@endif

@unless ($products->isEmpty())
    <div class="w-full h-auto flex flex-col lg:flex-row">
        <!-- Sidebar Section -->
        <div class="w-full lg:w-1/5 h-full pl-2 lg:p-6 lg:mt-36">
            @php
                // Filter products to exclude upcoming ones and extract unique categories
                $filteredCategories = $products->filter(function ($product) {
                    return $product->is_upcoming == 0;
                })->pluck('category')->unique()->values()->all();
        
                // Check if there are upcoming items
                $hasUpcomingItems = $products->contains('is_upcoming', 1);
            @endphp
        
            <h2 class="text-4xl font-bold text-velvet mb-4 text-center">Brands</h2>
            <div class="flex overflow-x-auto flex-row space-x-5 lg:space-x-0 lg:flex-col space-y-3">
                <!-- Display categories with non-upcoming products -->
                @foreach ($filteredCategories as $category)
                    <button class="filter-btn flex-shrink-0 bg-white text-velvet font-semibold py-3 px-4 rounded-lg border border-velvet hover:bg-velvet hover:text-white transition-colors duration-300"
                        onclick="toggleFilter(`{{ addslashes($category) }}`, this)">
                        {{ $category }}
                    </button>
                @endforeach
        
                <!-- Display "Upcoming Items" button if there are upcoming items -->
                @if ($hasUpcomingItems)
    <a href="{{ route('upcoming') }}" class="filter-btn flex-shrink-0 bg-white text-velvet text-center font-semibold py-3 px-4 rounded-lg border border-velvet hover:bg-velvet hover:text-white transition-colors duration-300">
        Upcoming Items
    </a>
@endif
            </div>
        </div> 

       <!-- Shop Section -->
<div class="w-full lg:w-4/5 h-auto p-6">
    <div class="w-full max-w-[90%] mx-auto">
        <!-- Search Component -->
        <x-search />
    </div>

    <div class="grid grid-cols-1 gap-12 w-full mx-auto pt-10 pb-20" id="product-list">
        @php
    // Filter products to exclude upcoming ones and then group by category
    $filteredProducts = $products->filter(function ($product) {
        return $product->is_upcoming == 0; // Only include non-upcoming products
    });
    $productsByCategory = $filteredProducts->groupBy('category');
@endphp

        @foreach ($productsByCategory as $category => $productsInCategory)
            <div class="w-full">
                <!-- Category Header -->
                <h2 class="text-4xl font-bold text-velvet tracking-widest uppercase mb-6 text-center">{{ $category }}</h2>

                <!-- Products Section with Grid Layout -->
                <div class="flex overflow-x-auto md:overflow-hidden md:grid md:grid-cols-3 gap-8">
                    @foreach ($productsInCategory as $product)
                        <button class="w-full min-w-[200px] h-auto p-4 md:rounded-lg shadow-lg flex flex-col items-center justify-between bg-white hover:bg-gray-100 transition-transform duration-300 transform hover:scale-105"
                            onclick="openModal({{ $product->id }})" id="product_{{ $product->id }}">
                            <div class="flex flex-col w-full h-full justify-between">
                                <div class="flex flex-col items-center text-center w-full">
                                    <h2 class="text-sm lg:text-md font-bold tracking-wider uppercase text-gray-800 mb-2"
                                        style="font-family: 'Roboto', sans-serif;">
                                        {{ $product->brand }} {{ $product->name }}
                                    </h2>
                                    <img src="{{ asset('storage/' . $product->variants->first()->images->first()->path) }}"
                                        alt="{{ $product->name }}"
                                        class="w-[140px] h-[180px] object-contain mb-4 rounded-md">
                                        
                                </div>

                                
                                <p id="price_{{ $product->id }}" class="text-md lg:text-lg font-light text-gray-900 mt-2"
                                    style="font-family: 'Roboto', sans-serif;">
                                    ₱{{ number_format($product->variants->first()->price, 0) }}
                                </p>
                            </div>
                        </button>

                        <!-- Modal Structure for Each Product -->
                        <dialog id="modal_{{ $product->id }}" class="modal">
                            <div class="modal-box w-full h-screen max-h-screen lg:max-w-5xl lg:h-[80vh] p-4 overflow-y-auto flex items-center justify-center rounded-none xl:rounded-lg">
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-50">✕</button>
                                </form>

                                <div id="alert_{{ $product->id }}"
                                    class="alert hidden absolute z-50 top-4 left-1/2 transform -translate-x-1/2 w-[80%] text-center rounded-md py-4 px-6 shadow-lg text-gray-800 text-2xl font-bold transition ease-in-out duration-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 stroke-current"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span id="alert-message_{{ $product->id }}"></span>
                                </div>
                                <div class="card w-full h-full flex flex-col lg:flex-row mx-auto items-center lg:items-center">
                                    <!-- Modal Content (Product Details) -->
                                    <div class="flex w-full lg:w-1/2 h-full flex-col justify-between p-4">
                                        <div class="flex flex-col items-center justify-center w-full h-full">
                                            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold tracking-wide text-gray-800 uppercase text-center pt-5"
                                                style="font-family: 'Roboto', sans-serif;">
                                                {{ $product->brand }} {{ $product->name }}
                                            </h2>
                                            <div class="carousel w-full no-touch-scroll" id="carousel_{{ $product->id }}">
                                                @foreach ($product->variants as $variant)
                                                    @foreach ($variant->images as $index => $image)
                                                        <div id="slide{{ $product->id }}-{{ $variant->id }}-{{ $index + 1 }}"
                                                            class="carousel-item relative w-full">
                                                            <img src="{{ asset('storage/' . $image->path) }}"
                                                                alt="{{ $product->name }}" class="w-full">
                                                            <div
                                                                class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                                                                @if ($index > 0)
                                                                    <a href="#slide{{ $product->id }}-{{ $variant->id }}-{{ $index }}"
                                                                        class="btn btn-circle">❮</a>
                                                                @else
                                                                    <a class="btn btn-circle opacity-50 cursor-not-allowed">❮</a>
                                                                @endif
                                                                @if ($index < count($variant->images) - 1)
                                                                    <a href="#slide{{ $product->id }}-{{ $variant->id }}-{{ $index + 2 }}"
                                                                        class="btn btn-circle">❯</a>
                                                                @else
                                                                    <a href="#slide{{ $product->id }}-{{ $variant->id }}-1"
                                                                        class="btn btn-circle">❯</a>
                                                                @endif
                                                            </div>

                                                            
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>
 <!-- Description Section -->
 <p id="variant_desc_{{ $product->id }}" class="mt-4 text-base text-gray-600 text-center">
    <!-- Description will be dynamically inserted here -->
</p>
                                            
                                        </div>
                                    </div>

                                    <div class="w-full lg:w-1/2 flex flex-col justify-center p-4 flex-1">
                                        <div class="card-body flex flex-col justify-between space-y-5 flex-1">
                                            <div>
                                                <label for="color_{{ $product->id }}" class="block text-sm font-medium text-gray-700">Color</label>
                                                <select id="color_{{ $product->id }}" name="color" class="mt-2 block w-full px-4 py-2 text-base border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md cursor-pointer transition duration-200">
                                                    @php
                                                        $uniqueColors = $product->variants->pluck('color')->unique();
                                                    @endphp
                                                    @foreach ($uniqueColors as $color)
                                                        <option value="{{ $color }}">{{ $color }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label for="size_{{ $product->id }}" class="block text-sm font-medium text-gray-700">Size</label>
                                                <select id="size_{{ $product->id }}" name="size" class="mt-2 block w-full px-4 py-2 text-base border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md transition duration-200">
                                                    @php
                                                        $uniqueSizes = $product->variants->pluck('size')->unique();
                                                    @endphp
                                                    @foreach ($uniqueSizes as $size)
                                                        <option value="{{ $size }}">{{ $size }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <p id="modal_price_{{ $product->id }}" class="text-lg font-semibold text-gray-900">
                                                    ₱{{ number_format($product->variants->first()->price, 0) }}
                                                </p>
                                            </div>

                                            <!-- Quantity Display -->
<p id="quantity_{{ $product->id }}"></p>

<!-- Quantity Chooser -->
<div class="flex items-center mt-2">
    <!-- Decrease Button -->
    <button class="px-2 py-1 bg-gray-200 text-gray-800 rounded" onclick="decreaseQuantity({{ $product->id }})">-</button>

    <!-- Quantity Input Field -->
    <input id="chosen_quantity_{{ $product->id }}" 
           type="number" 
           value="1" 
           min="1" 
           max="{{ $product->variants->first()->quantity }}"
           class="mx-2 w-16 text-center border rounded appearance-none" 
           oninput="validateQuantityInput({{ $product->id }})" />

    <!-- Increase Button -->
    <button class="px-2 py-1 bg-gray-200 text-gray-800 rounded" onclick="increaseQuantity({{ $product->id }})">+</button>
</div>
                                            <div class="mt-6">
                                                <button type="button"
                                                class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-300"
                                                onclick="addToCart({{ $product->id }}, updateVariant({{ $product->id }}, document.getElementById('color_{{ $product->id }}').value, document.getElementById('size_{{ $product->id }}') ? document.getElementById('size_{{ $product->id }}').value : null).id, document.getElementById('chosen_quantity_{{ $product->id }}').value)">
                                                Add to Cart
                                            </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </dialog>

                        <script>
                        // Function to increase the chosen quantity
function increaseQuantity(productId) {
    var chosenQuantityInput = document.getElementById('chosen_quantity_' + productId);
    var maxQuantity = parseInt(chosenQuantityInput.max); // Use max attribute from the input
    var currentQuantity = parseInt(chosenQuantityInput.value);

    if (currentQuantity < maxQuantity) {
        chosenQuantityInput.value = currentQuantity + 1;
    }
}

// Function to decrease the chosen quantity
function decreaseQuantity(productId) {
    var chosenQuantityInput = document.getElementById('chosen_quantity_' + productId);
    var currentQuantity = parseInt(chosenQuantityInput.value);

    if (currentQuantity > 1) {
        chosenQuantityInput.value = currentQuantity - 1;
    }
}

// Function to validate and enforce the max quantity limit when user manually inputs a number
function validateQuantityInput(productId) {
    var chosenQuantityInput = document.getElementById('chosen_quantity_' + productId);
    var maxQuantity = parseInt(chosenQuantityInput.max); // Get the max value set for this variant
    var currentQuantity = parseInt(chosenQuantityInput.value);

    if (currentQuantity > maxQuantity) {
        // If user input exceeds max, set it to max
        chosenQuantityInput.value = maxQuantity;
    } else if (currentQuantity < 1) {
        // Ensure the quantity doesn't go below 1
        chosenQuantityInput.value = 1;
    }
}
                            function openModal(productId) {
                                document.getElementById('modal_' + productId).showModal();
                                const selectedColor = document.getElementById(`color_${productId}`).value;
                                const selectedSize = document.getElementById(`size_${productId}`).value;
                                updateVariant(productId, selectedColor, selectedSize);
                            }
                        
                            function updateVariant(productId, selectedColor, selectedSize) {
    var variants = @json($products->pluck('variants')->flatten()->groupBy('product_id'));
    var productVariants = variants[productId];
    if (!productVariants) {
        return null;
    }
    var variant = productVariants.find(variant => variant.color === selectedColor && variant.size === selectedSize);
    if (variant) {
        // Format the price
        var formattedPrice = '₱' + variant.price.toLocaleString('en-US', {
            maximumFractionDigits: 0
        });
         // Update the quantity display
         var quantityElement = document.getElementById('quantity_' + productId);
        if (quantityElement) {
            quantityElement.textContent = "Stock: " + variant.quantity;
        }

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
        
        // Update price elements
        document.getElementById('price_' + productId).textContent = formattedPrice;
        document.getElementById('modal_price_' + productId).textContent = formattedPrice;

        // Update the description
        var descElement = document.getElementById(`variant_desc_${productId}`);
        if (descElement) {
            descElement.textContent = variant.desc ? variant.desc : "No description available.";
        }

        // Update the images in the carousel
        var carousel = document.getElementById('carousel_' + productId);
        if (carousel) {
            carousel.innerHTML = ''; // Clear existing images
            variant.images.forEach((image, index) => {
                var slide = document.createElement('div');
                slide.id = 'slide' + productId + '-' + variant.id + '-' + (index + 1);
                slide.className = 'carousel-item relative w-full h-full flex justify-center items-center ' + (index === 0 ? 'active' : '');
                var img = document.createElement('img');
                img.src = '{{ asset('storage/') }}' + '/' + image.path;
                img.alt = 'Product Image';
                img.className = 'w-[250px] h-[300px] object-contain py-3';
                slide.appendChild(img);
                var nav = document.createElement('div');
                nav.className = 'absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2';
                if (index > 0) {
                    var prevBtn = document.createElement('a');
                    prevBtn.href = '#slide' + productId + '-' + variant.id + '-' + index;
                    prevBtn.className = 'btn btn-circle bg-gray-800 text-white';
                    prevBtn.textContent = '❮';
                    nav.appendChild(prevBtn);
                } else {
                    var prevBtnDisabled = document.createElement('a');
                    prevBtnDisabled.className = 'btn btn-circle bg-gray-300 text-gray-600 cursor-not-allowed';
                    prevBtnDisabled.textContent = '❮';
                    nav.appendChild(prevBtnDisabled);
                }
                if (index < variant.images.length - 1) {
                    var nextBtn = document.createElement('a');
                    nextBtn.href = '#slide' + productId + '-' + variant.id + '-' + (index + 2);
                    nextBtn.className = 'btn btn-circle bg-gray-800 text-white';
                    nextBtn.textContent = '❯';
                    nav.appendChild(nextBtn);
                } else {
                    var nextBtnDisabled = document.createElement('a');
                    nextBtnDisabled.className = 'btn btn-circle bg-gray-300 text-gray-600 cursor-not-allowed';
                    nextBtnDisabled.textContent = '❯';
                    nav.appendChild(nextBtnDisabled);
                }
                slide.appendChild(nav);
                carousel.appendChild(slide);
            });
        }
        return variant;
    }
    return null;
}
                        </script>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                showProducts();
                                var productId = {{ $product->id }};
                                var colorDropdown = document.getElementById('color_{{ $product->id }}');
                                var sizeDropdown = document.getElementById('size_{{ $product->id }}');
                                var priceElement = document.getElementById('price_{{ $product->id }}');
                                var modalPriceElement = document.getElementById(
                                    'modal_price_{{ $product->id }}'); // Added line for modal price element
                        
                                colorDropdown.addEventListener('change', function() {
                                    var selectedColor = this.value;
                                    var availableSizes = getAvailableSizes(productId, selectedColor);
                                    populateSizesDropdown(sizeDropdown, availableSizes);
                                    var firstSize = availableSizes[0];
                                    var variant = updateVariant(productId, selectedColor, firstSize);
                                    if (variant) {
                                        var formattedPrice = '₱' + variant.price.toLocaleString('en-US', {
                                            maximumFractionDigits: 0
                                        });
                                        priceElement.textContent = formattedPrice;
                                        modalPriceElement.textContent = formattedPrice; // Added line to update modal price
                                    }
                                });
                        
                                var initialColor = colorDropdown.value;
                                var initialSizes = getAvailableSizes(productId, initialColor);
                                populateSizesDropdown(sizeDropdown, initialSizes);
                        
                                sizeDropdown.addEventListener('change', function() {
                                    var selectedSize = this.value;
                                    var variant = updateVariant(productId, colorDropdown.value, selectedSize);
                                    if (variant) {
                                        var formattedPrice = '₱' + variant.price.toLocaleString('en-US', {
                                            maximumFractionDigits: 0
                                        });
                                        priceElement.textContent = formattedPrice;
                                        modalPriceElement.textContent = formattedPrice; // Added line to update modal price
                                    }
                                });
                        
                                var variants = @json($products->pluck('variants')->flatten()->groupBy('product_id'));
                                var productVariants = variants[productId];
                                if (productVariants) {
                                    var firstVariant = productVariants.find(variant => variant.color === initialColor && variant
                                        .size === initialSizes[0]);
                                    if (firstVariant) {
                                        var formattedPrice = '₱' + firstVariant.price.toLocaleString('en-US', {
                                            maximumFractionDigits: 0
                                        });
                                        priceElement.textContent = formattedPrice;
                                        modalPriceElement.textContent = formattedPrice; // Added line to update modal price
                                    }
                                }
                            });
                        
                            function showProducts() {
                                const productsData = @json($products);
                                productsData.forEach(product => {
                                    const skeleton = document.getElementById(`skeleton_${product.id}`);
                                    const productElement = document.getElementById(`product_${product.id}`);
                                    if (skeleton && productElement) {
                                        productElement.classList.remove('hidden');
                                        skeleton.classList.add('hidden');
                                    }
                                });
                            }
                        </script>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>




<script>
// Function to get available sizes for a given product ID and color
function getAvailableSizes(productId, selectedColor) {
    var variants = @json($products->pluck('variants')->flatten()->groupBy('product_id'));
    var productVariants = variants[productId];
    if (!productVariants) {
        return [];
    }
    var sizes = productVariants.filter(variant => variant.color === selectedColor).map(variant => variant.size);
    return sizes;
}

// Function to populate sizes dropdown with given sizes
function populateSizesDropdown(sizeDropdown, sizes) {
    sizeDropdown.innerHTML = ''; // Clear existing options
    sizes.forEach(size => {
        var option = document.createElement('option');
        option.value = size;
        option.innerText = size;
        sizeDropdown.appendChild(option);
    });
}



const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};

function addToCart(productId, variantId, quantity) {
    // Check if the user is logged in
    if (!isAuthenticated) {
        // Redirect the user to the login route
        window.location.href = '{{ route('register') }}';
        return; // Stop further execution of the function
    }

    // Make an AJAX request to add the item to the cart with the chosen quantity
    fetch('{{ route('cart.add') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                variant_id: variantId,
                quantity: quantity  // Pass the chosen quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            // Check if the request was successful
            if (data.success) {
                fetchCartItems();
                fetchCartData();
                showPopupMessage(productId, data.message);
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

function showPopupMessage(productId, message) {
    const popupMessageContainer = document.getElementById('alert_' + productId);
    const messageSpan = document.getElementById('alert-message_' + productId);

    // Set the message content
    messageSpan.textContent = message;

    // Adjust the background color based on the message
    if (message.includes('already in your cart')) {
        popupMessageContainer.classList.remove('bg-emerald-300');
        popupMessageContainer.classList.add('bg-amber-300');
    } else {
        popupMessageContainer.classList.remove('bg-amber-300');
        popupMessageContainer.classList.add('bg-emerald-300');
    }

    // Show the alert message
    popupMessageContainer.classList.remove('hidden');

    // Hide the alert message after a certain time
    setTimeout(() => {
        popupMessageContainer.classList.add('hidden');
    }, 2000); // Adjust the timeout value as needed
}





let activeFilter = '';
let productsData = []; // Initialize the productsData variable

function toggleFilter(category, button) {
    const searchInput = document.getElementById('searchInput');
    // Clear the search bar
    if (searchInput && searchInput.value.trim() !== '') {
        searchInput.value = '';
    }
    if (activeFilter === category) {
        // Deactivate the filter
        activeFilter = '';
        // Display products by category if no filter is active
        displayProductsByCategory();
    } else {
        // Activate the new filter
        activeFilter = category;
        filterProducts(category, button);
    }
    // Add active class to the clicked button and remove from others
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => {
        btn.classList.remove('bg-velvet', 'text-white');
        btn.classList.add('bg-white', 'text-velvet');
    });
    if (activeFilter !== '') {
        button.classList.remove('bg-white', 'text-velvet');
        button.classList.add('bg-velvet', 'text-white');
    }
}

function displayProductsByCategory() {
    const productsByCategory = @json($products->where('is_upcoming', 0)->groupBy('category'));

    const productList = document.getElementById('product-list');
    if (!productList) {
        console.error('Element with ID "product-list" not found.');
        return;
    }
    productList.innerHTML = '';

    // Clear old products data and prepare to update with current products
    productsData = [];

    Object.entries(productsByCategory).forEach(([category, productsInCategory]) => {
        // Add the current products to productsData
        productsData = productsData.concat(productsInCategory);

        let categorySectionHtml = `
            <div class="w-full">
                <h2 class="text-4xl font-bold text-velvet tracking-widest uppercase mb-6 text-center">${category}</h2>
                <div class="flex overflow-x-auto md:overflow-hidden md:grid md:grid-cols-3 gap-8">
        `;

        productsInCategory.forEach(product => {
            categorySectionHtml += `
                <button class="w-full min-w-[200px] h-auto p-4 md:rounded-lg shadow-lg flex flex-col items-center justify-between bg-white hover:bg-gray-100 transition-transform duration-300 transform hover:scale-105"
                        onclick="openModal(${product.id})" id="product_${product.id}">
                    <div class="flex flex-col w-full h-full justify-between">
                        <div class="flex flex-col items-center text-center w-full">
                            <h2 class="text-sm lg:text-md font-bold tracking-wider uppercase text-gray-800 mb-2"
                                style="font-family: 'Roboto', sans-serif;">
                                ${product.brand} ${product.name}
                            </h2>
                            <img src="/storage/${product.variants[0].images[0].path}" alt="${product.name}"
                                class="w-[140px] h-[180px] object-contain mb-4 rounded-md">
                        </div>
                        <p id="price_${product.id}" class="text-md lg:text-lg font-light text-gray-900 mt-2"
                            style="font-family: 'Roboto', sans-serif;">
                            ₱${product.variants[0].price.toLocaleString('en-US', { maximumFractionDigits: 0 })}
                        </p>
                    </div>
                </button>

                <!-- Modal Structure for Each Product -->
                <dialog id="modal_${product.id}" class="modal">
                    <div class="modal-box w-full h-screen max-h-screen lg:max-w-5xl lg:h-[80vh] p-4 overflow-y-auto flex items-center justify-center rounded-none xl:rounded-lg">
                        <form method="dialog">
                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-50">✕</button>
                        </form>

                        <div id="alert_${product.id}"
                            class="alert hidden absolute z-50 top-4 left-1/2 transform -translate-x-1/2 w-[80%] text-center rounded-md py-4 px-6 shadow-lg text-gray-800 text-2xl font-bold transition ease-in-out duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 stroke-current"
                                fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span id="alert-message_${product.id}"></span>
                        </div>

                        <div class="card w-full h-full flex flex-col lg:flex-row mx-auto items-center lg:items-center">
                            <!-- Modal Content (Product Details) -->
                            <div class="flex w-full lg:w-1/2 h-full flex-col justify-between p-4">
                                <div class="flex flex-col items-center justify-center w-full h-full">
                                    <h2 class="text-lg sm:text-xl lg:text-2xl font-bold tracking-wide text-gray-800 uppercase text-center pt-5"
                                        style="font-family: 'Roboto', sans-serif;">
                                        ${product.brand} ${product.name}
                                    </h2>
                                    <div class="carousel w-full no-touch-scroll" id="carousel_${product.id}">
                                        ${product.variants.map((variant, variantIndex) => variant.images.map((image, imageIndex) => `
                                            <div id="slide${product.id}-${variant.id}-${imageIndex + 1}" class="carousel-item relative w-full h-full flex justify-center items-center ${imageIndex === 0 ? 'active' : ''}">
                                                <img src="/storage/${image.path}" alt="${product.name}" class="w-[250px] h-[300px] object-contain py-3">
                                                <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                                    ${imageIndex > 0 ? `<a href="#slide${product.id}-${variant.id}-${imageIndex}" class="btn btn-circle">❮</a>` : `<span class="btn btn-circle opacity-50">❮</span>`}
                                                    ${imageIndex < variant.images.length - 1 ? `<a href="#slide${product.id}-${variant.id}-${imageIndex + 2}" class="btn btn-circle">❯</a>` : `<span class="btn btn-circle opacity-50">❯</span>`}
                                                </div>
                                            </div>
                                        `).join('')).join('')}
                                    </div>
                                    <!-- Description Section -->
                                    <p id="variant_desc_${product.id}" class="mt-4 text-base text-gray-600 text-center">
                                        ${product.variants[0].desc || "No description available."}
                                    </p>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 flex flex-col justify-center p-4 flex-1">
                                <div class="card-body flex flex-col justify-between space-y-5 flex-1">
                                    <div>
                                        <label for="color_${product.id}" class="block text-sm font-medium text-gray-700">Color</label>
                                        <select id="color_${product.id}" name="color" class="mt-2 block w-full px-4 py-2 text-base border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md cursor-pointer transition duration-200">
                                            ${[...new Set(product.variants.map(variant => variant.color))].map(color => `<option value="${color}">${color}</option>`).join('')}
                                        </select>
                                    </div>

                                    <div>
                                        <label for="size_${product.id}" class="block text-sm font-medium text-gray-700">Size</label>
                                        <select id="size_${product.id}" name="size" class="mt-2 block w-full px-4 py-2 text-base border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md transition duration-200">
                                            ${[...new Set(product.variants.map(variant => variant.size))].map(size => `<option value="${size}">${size}</option>`).join('')}
                                        </select>
                                    </div>

                                    <p id="modal_price_${product.id}" class="text-lg font-semibold text-gray-900">
    ₱${product.variants[0].price.toLocaleString('en-US', { maximumFractionDigits: 0 })}
</p>

                                    <!-- Stock Display -->
                                    <p id="quantity_${product.id}" class="text-md lg:text-lg font-light text-gray-900 mt-2">
                                        Stock: ${product.variants[0].quantity}
                                    </p>

                                    <!-- Quantity Chooser -->
                                    <div class="flex items-center mt-2">
                                        <button class="px-2 py-1 bg-gray-200 text-gray-800 rounded" onclick="decreaseQuantity(${product.id})">-</button>
                                        <input id="chosen_quantity_${product.id}" type="number" value="1" min="1" max="${product.variants[0].quantity}" class="mx-2 w-16 text-center border rounded appearance-none" oninput="validateQuantityInput(${product.id})" />
                                        <button class="px-2 py-1 bg-gray-200 text-gray-800 rounded" onclick="increaseQuantity(${product.id})">+</button>
                                    </div>

                                    <div class="mt-6">
                                        <button type="button"
    class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-300"
    onclick="addToCart(${product.id}, updateVariant(${product.id}, document.getElementById('color_${product.id}').value, document.getElementById('size_${product.id}') ? document.getElementById('size_${product.id}').value : null).id, document.getElementById('chosen_quantity_${product.id}').value)">
    Add to Cart
</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </dialog>
            `;
        });

        categorySectionHtml += `</div></div>`;
        productList.insertAdjacentHTML('beforeend', categorySectionHtml);
    });

    // Attach event listeners after updating the product list
    attachEventListeners();
}

function filterProducts(category, button) {
    fetch(`{{ url('/products/filter') }}?category=${category}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            productsData = data; // Update productsData with the fetched products

            const productList = document.getElementById('product-list');
            if (!productList) {
                console.error('Element with ID "product-list" not found.');
                return;
            }
            productList.innerHTML = ''; // Clear existing products

            if (data.length > 0) {
                let productGridHtml = `
                    <div class="flex overflow-x-auto md:overflow-hidden md:grid md:grid-cols-3 gap-8 w-full mx-auto pt-10 pb-20">
                `;

                data.forEach(product => {
                    productGridHtml += `
                        <button class="w-full min-w-[200px] h-auto p-4 md:rounded-lg shadow-lg flex flex-col items-center justify-between bg-white hover:bg-gray-100 transition-transform duration-300 transform hover:scale-105"
                                onclick="openModal(${product.id})" id="product_${product.id}">
                            <div class="flex flex-col w-full h-full justify-between">
                                <div class="flex flex-col items-center text-center w-full">
                                    <h2 class="text-sm lg:text-md font-bold tracking-wider uppercase text-gray-800 mb-2"
                                        style="font-family: 'Roboto', sans-serif;">
                                        ${product.brand} ${product.name}
                                    </h2>
                                    <img src="/storage/${product.variants[0].images[0].path}" alt="${product.name}"
                                        class="w-[140px] h-[180px] object-contain mb-4">
                                </div>
                                <p id="price_${product.id}" class="text-md lg:text-lg font-light text-gray-900 mt-2"
                                    style="font-family: 'Roboto', sans-serif;">
                                    ₱${product.variants[0].price.toLocaleString('en-US', { maximumFractionDigits: 0 })}
                                </p>
                            </div>
                        </button>

                        <!-- Modal Structure for Each Product -->
                        <dialog id="modal_${product.id}" class="modal">
                            <div class="modal-box w-full h-screen max-h-screen lg:max-w-5xl lg:h-[80vh] p-4 overflow-y-auto flex items-center justify-center rounded-none xl:rounded-lg">
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-50">✕</button>
                                </form>

                                <div id="alert_${product.id}"
                                    class="alert hidden absolute z-50 top-4 left-1/2 transform -translate-x-1/2 w-[80%] text-center rounded-md py-4 px-6 shadow-lg text-gray-800 text-2xl font-bold transition ease-in-out duration-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 stroke-current"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span id="alert-message_${product.id}"></span>
                                </div>

                                <div class="card w-full h-full flex flex-col lg:flex-row mx-auto items-center lg:items-center">
                                    <!-- Modal Content (Product Details) -->
                                    <div class="flex w-full lg:w-1/2 h-full flex-col justify-between p-4">
                                        <div class="flex flex-col items-center justify-center w-full h-full">
                                            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold tracking-wide text-gray-800 uppercase text-center pt-5"
                                                style="font-family: 'Roboto', sans-serif;">
                                                ${product.brand} ${product.name}
                                            </h2>
                                            <div class="carousel w-full no-touch-scroll" id="carousel_${product.id}">
                                                ${product.variants.map((variant, variantIndex) => variant.images.map((image, imageIndex) => `
                                                    <div id="slide${product.id}-${variant.id}-${imageIndex + 1}" class="carousel-item relative w-full h-full flex justify-center items-center ${imageIndex === 0 ? 'active' : ''}">
                                                        <img src="/storage/${image.path}" alt="${product.name}" class="w-[250px] h-[300px] object-contain py-3">
                                                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                                            ${imageIndex > 0 ? `<a href="#slide${product.id}-${variant.id}-${imageIndex}" class="btn btn-circle">❮</a>` : `<span class="btn btn-circle opacity-50">❮</span>`}
                                                            ${imageIndex < variant.images.length - 1 ? `<a href="#slide${product.id}-${variant.id}-${imageIndex + 2}" class="btn btn-circle">❯</a>` : `<span class="btn btn-circle opacity-50">❯</span>`}
                                                        </div>
                                                    </div>
                                                `).join('')).join('')}
                                            </div>
                                            <!-- Description Section -->
                                            <p id="variant_desc_${product.id}" class="mt-4 text-base text-gray-600 text-center">
                                                ${product.variants[0].desc || "No description available."}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="w-full lg:w-1/2 flex flex-col justify-center p-4 flex-1">
                                        <div class="card-body flex flex-col justify-between space-y-5 flex-1">
                                            <div>
                                                <label for="color_${product.id}" class="block text-sm font-medium text-gray-700">Color</label>
                                                <select id="color_${product.id}" name="color" class="mt-2 block w-full px-4 py-2 text-base border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md cursor-pointer transition duration-200">
                                                    ${[...new Set(product.variants.map(variant => variant.color))].map(color => `<option value="${color}">${color}</option>`).join('')}
                                                </select>
                                            </div>

                                            <div>
                                                <label for="size_${product.id}" class="block text-sm font-medium text-gray-700">Size</label>
                                                <select id="size_${product.id}" name="size" class="mt-2 block w-full px-4 py-2 text-base border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md transition duration-200">
                                                    ${[...new Set(product.variants.map(variant => variant.size))].map(size => `<option value="${size}">${size}</option>`).join('')}
                                                </select>
                                            </div>

                                            <p id="modal_price_${product.id}" class="text-lg font-semibold text-gray-900">
    ₱${product.variants[0].price.toLocaleString('en-US', { maximumFractionDigits: 0 })}
</p>

                                            <!-- Stock Display -->
                                            <p id="quantity_${product.id}" class="text-md lg:text-lg font-light text-gray-900 mt-2">
                                                Stock: ${product.variants[0].quantity}
                                            </p>

                                            <!-- Quantity Chooser -->
                                            <div class="flex items-center mt-2">
                                                <button class="px-2 py-1 bg-gray-200 text-gray-800 rounded" onclick="decreaseQuantity(${product.id})">-</button>
                                                <input id="chosen_quantity_${product.id}" type="number" value="1" min="1" max="${product.variants[0].quantity}" class="mx-2 w-16 text-center border rounded appearance-none" oninput="validateQuantityInput(${product.id})" />
                                                <button class="px-2 py-1 bg-gray-200 text-gray-800 rounded" onclick="increaseQuantity(${product.id})">+</button>
                                            </div>

                                            <div class="mt-6">
                                               <button type="button"
        class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-300"
        onclick="addToCart(${product.id}, updateVariant(${product.id}, document.getElementById('color_${product.id}').value, document.getElementById('size_${product.id}') ? document.getElementById('size_${product.id}').value : null).id, document.getElementById('chosen_quantity_${product.id}').value)">
        Add to Cart
    </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </dialog>
                    `;
                });

                productGridHtml += `</div>`;
                productList.insertAdjacentHTML('beforeend', productGridHtml);

                // Attach event listeners after updating product list
                attachEventListeners();
            } else {
                productList.innerHTML = `
                    <div class="w-full h-64 flex justify-center items-center py-10">
                        <p class="text-center text-gray-500 text-xl">No products found for the selected category.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function attachEventListeners() {
    productsData.forEach(product => {
        const colorDropdown = document.getElementById('color_' + product.id);
        const sizeDropdown = document.getElementById('size_' + product.id);

        colorDropdown.addEventListener('change', function() {
            const selectedColor = this.value;
            window.handleColorChange(product.id, selectedColor);
        });

        sizeDropdown.addEventListener('change', function() {
            const selectedSize = this.value;
            window.handleSizeChange(product.id, selectedSize);
        });
    });
}


function getProductColors(variants) {
    const colors = variants.map(variant => variant.color);
    return [...new Set(colors)];
}

function getProductSizes(variants, selectedColor) {
    const sizes = variants.filter(variant => variant.color === selectedColor).map(variant => variant.size);
    return [...new Set(sizes)];
}


window.handleColorChange = function(productId, selectedColor) {
    const sizeDropdown = document.getElementById(`size_${productId}`);
    const product = productsData.find(product => product.id === productId);
    if (!product) {
        console.error("Product not found");
        return;
    }
    const availableSizes = window.getProductSizes(product.variants, selectedColor);
    sizeDropdown.innerHTML = "";
    availableSizes.forEach(size => {
        const option = document.createElement("option");
        option.value = size;
        option.textContent = size;
        sizeDropdown.appendChild(option);
    });
    window.updateVariants(productId, selectedColor, sizeDropdown.value);
    document.getElementById(`color_${productId}`).setAttribute('data-selected-color', selectedColor);
};

window.handleSizeChange = function(productId, selectedSize) {
    const selectedColor = document.getElementById(`color_${productId}`).getAttribute('data-selected-color');
    if (!selectedColor) {
        console.error("Selected color not found");
        return;
    }
    window.updateVariants(productId, selectedColor, selectedSize);
};


window.updateVariants = function(productId, selectedColor, selectedSize) {
    const product = productsData.find(product => product.id === productId);
    if (!product) {
        return null;
    }
    const variants = product.variants.filter(variant => variant.color === selectedColor && variant.size === selectedSize);
    if (variants.length === 0) {
        return null;
    }
    const selectedVariant = variants[0];
    const formattedPrice = '₱' + selectedVariant.price.toLocaleString('en-US', {
        maximumFractionDigits: 0
    });

    // Update price in the modal
    const priceElement = document.getElementById(`modal_price_${productId}`);
    if (priceElement) {
        priceElement.textContent = formattedPrice;
    }

    // Update description in the modal
    const descElement = document.getElementById(`variant_desc_${productId}`);
    if (descElement) {
        descElement.textContent = selectedVariant.desc || "No description available.";
    }

    // Update stock and quantity chooser in the modal
    const stockElement = document.getElementById(`quantity_${productId}`);
    if (stockElement) {
        stockElement.textContent = `Stock: ${selectedVariant.quantity}`;
    }

    const chosenQuantityInput = document.getElementById(`chosen_quantity_${productId}`);
    if (chosenQuantityInput) {
        chosenQuantityInput.value = 1;
        chosenQuantityInput.max = selectedVariant.quantity; // Set the max to the current stock
    }

    // Update images in the carousel
    const carousel = document.getElementById(`carousel_${productId}`);
    if (carousel) {
        carousel.innerHTML = selectedVariant.images.map((image, imageIndex) => `
            <div id="slide${productId}-${selectedVariant.id}-${imageIndex + 1}" class="carousel-item relative w-full h-full flex justify-center items-center ${imageIndex === 0 ? 'active' : ''}">
                <img src="{{ asset('storage/') }}/${image.path}" alt="${product.name}" class="w-[250px] h-[300px] object-contain py-3">
                <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                    ${imageIndex > 0 ? `<a href="#slide${productId}-${selectedVariant.id}-${imageIndex}" class="btn btn-circle">❮</a>` : `<a class="btn btn-circle opacity-50">❮</a>`}
                    ${imageIndex < selectedVariant.images.length - 1 ? `<a href="#slide${productId}-${selectedVariant.id}-${imageIndex + 2}" class="btn btn-circle">❯</a>` : `<a class="btn btn-circle opacity-50">❯</a>`}
                </div>
            </div>
        `).join('');
    }

    return selectedVariant;
};

window.handleAddToCart = function(productId) {
    
    
    // Check if the user is authenticated
    if (!isAuthenticated) {
        // Redirect the user to the login page
        window.location.href = '{{ route('register') }}';
        return;
    }

    // Get the selected color and size
    const colorDropdown = document.getElementById(`color_${productId}`);
    const sizeDropdown = document.getElementById(`size_${productId}`);
    
    if (!colorDropdown || !sizeDropdown) {
        console.error("Color or size dropdown not found for product:", productId);
        return;
    }

    const selectedColor = colorDropdown.value;
    const selectedSize = sizeDropdown.value;

    // Find the variant with the selected options
    const product = productsData.find(product => product.id === productId);
    if (!product) {
        console.error("Product not found in productsData:", productId);
        return;
    }

    const variant = product.variants.find(variant => variant.color === selectedColor && variant.size === selectedSize);
    if (!variant) {
        console.error("Variant not found for the selected options");
        return;
    }

    // Proceed to add the item to the cart
    addToCart(productId, variant.id);
};
</script>

@endunless