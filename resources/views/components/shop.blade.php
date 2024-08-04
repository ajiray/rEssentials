<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    html {
        scroll-behavior: smooth;
    }

    .no-touch-scroll {
        touch-action: none;
        /* Disables touch interaction */
    }
</style>
@if ($products->isEmpty())
    <div class="w-full h-screen bg-gold flex justify-center items-center flex-col">
        <h1 class="text-3xl md:text-4xl text-velvet font-bold tracking-widest xl:text-5xl text-center">Thank
            you for supporting R Essentials!
        </h1>
        <p class="text-lg md:text-2xl text-center mt-4 xl:text-2xl text-velvet">Our inventory is currently sold out. We
            appreciate your continued support.
        </p>
    </div>
@endif
@unless ($products->isEmpty())
    <div class="max-w-[90%] mx-auto pt-[70px]">

        <x-search />

    </div>
    @php
        // Extract unique categories from the product descriptions
        $categories = collect($products)
            ->map(function ($product) {
                return explode(' ', trim($product->category))[0];
            })
            ->unique()
            ->values()
            ->all();
    @endphp

    <div
        class="w-[90%] mx-auto h-auto flex overflow-x-auto justify-start xl:justify-center items-center mt-10 space-x-4 pt-3 mb-5">
        @foreach ($categories as $category)
            <button class="filter-btn flex-shrink-0 bg-white text-velvet font-semibold py-2 px-4 rounded"
                onclick="toggleFilter('{{ $category }}', this)">{{ $category }}</button>
        @endforeach
    </div>


    <div class="grid grid-cols-0 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4 w-[95%] mx-auto pb-20 h-auto px-5 md:px-10"
        id="product-list">


        @foreach ($products as $product)
            <div class="skeleton xl:w-[20%] h-[400px] flex xl:flex-col mx-auto mt-20" id="skeleton_{{ $product->id }}">
            </div>

            <button
                class="w-full h-auto p-4 sm:p-6 lg:p-8 rounded-lg shadow-lg flex flex-col items-center justify-between bg-white hover:bg-gray-100 transition-transform duration-300 transform hover:scale-105 mt-3"
                onclick="openModal({{ $product->id }})" id="product_{{ $product->id }}">
                <div class="flex flex-col w-full h-full justify-between">
                    <div class="flex flex-col items-center text-center w-full">
                        <h2 class="text-base sm:text-lg lg:text-xl font-bold tracking-wider uppercase text-gray-800 mb-2"
                            style="font-family: 'Roboto', sans-serif;">
                            {{ $product->brand }} {{ $product->name }}
                        </h2>
                        <img src="{{ asset('storage/' . $product->variants->first()->images->first()->path) }}"
                            alt="{{ $product->name }}"
                            class="w-[120px] sm:w-[150px] lg:w-[180px] h-[160px] sm:h-[200px] lg:h-[240px] object-contain mb-4 rounded-md">
                    </div>
                    <p id="price_{{ $product->id }}" class="text-base sm:text-lg lg:text-xl font-light text-gray-900 mt-2"
                        style="font-family: 'Roboto', sans-serif;">
                        ₱{{ number_format($product->variants->first()->price, 0) }}
                    </p>
                </div>
            </button>


            <dialog id="modal_{{ $product->id }}" class="modal">
                <div
                    class="modal-box w-full h-screen max-h-screen lg:max-w-5xl lg:h-[80vh] p-4 overflow-y-auto flex items-center justify-center rounded-none xl:rounded-lg">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-50">✕</button>
                    </form>
                    <div id="alert_{{ $product->id }}"
                        class="alert hidden absolute top-4 left-1/2 transform -translate-x-1/2 w-[80%] text-center rounded-md py-4 px-6 shadow-lg text-gray-800 text-2xl font-bold transition ease-in-out duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 stroke-current"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span id="alert-message_{{ $product->id }}"></span>
                    </div>



                    <div class="card w-full h-full flex flex-col lg:flex-row mx-auto items-center lg:items-center">
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

                            </div>
                        </div>

                        <div class="w-full lg:w-1/2 flex flex-col justify-center p-4 flex-1">
                            <div class="card-body flex flex-col justify-between space-y-5 flex-1">
                                <div>
                                    <label for="color_{{ $product->id }}"
                                        class="block text-sm font-medium text-gray-700">Color</label>
                                    <select id="color_{{ $product->id }}" name="color"
                                        onchange="updateVariant({{ $product->id }})"
                                        class="mt-2 block w-full px-4 py-2 text-base border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md cursor-pointer transition duration-200">
                                        @php
                                            $uniqueColors = $product->variants->pluck('color')->unique();
                                        @endphp
                                        @foreach ($uniqueColors as $color)
                                            <option value="{{ $color }}">{{ $color }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="size_{{ $product->id }}"
                                        class="block text-sm font-medium text-gray-700">Size</label>
                                    <select id="size_{{ $product->id }}" name="size"
                                        onchange="updateVariant({{ $product->id }})"
                                        class="mt-2 block w-full px-4 py-2 text-base border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md transition duration-200">
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
                                        ₱</p>
                                </div>

                                <div class="mt-6">
                                    <button type="button"
                                        class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-300"
                                        onclick="addToCart({{ $product->id }}, updateVariant({{ $product->id }}, document.getElementById('color_{{ $product->id }}').value, document.getElementById('size_{{ $product->id }}') ? document.getElementById('size_{{ $product->id }}').value : null).id)">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </dialog>





            <script>
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
                        var formattedPrice = '₱' + variant.price.toLocaleString('en-US', {
                            maximumFractionDigits: 0
                        });
                        document.getElementById('price_' + productId).textContent = formattedPrice;
                        document.getElementById('modal_price_' + productId).textContent =
                            formattedPrice; // Added line to update modal price

                        var carousel = document.getElementById('carousel_' + productId);
                        carousel.innerHTML = '';
                        variant.images.forEach((image, index) => {
                            var slide = document.createElement('div');
                            slide.id = 'slide' + productId + '-' + variant.id + '-' + (index + 1);
                            slide.className = 'carousel-item relative w-full h-full flex justify-center items-center ' + (
                                index === 0 ? 'active' : '');
                            var img = document.createElement('img');
                            img.src = '{{ asset('storage/') }}' + '/' + image.path;
                            img.alt = 'Product Image';
                            img.className = 'w-[250px] h-[300px] object-contain py-3';
                            slide.appendChild(img);
                            var nav = document.createElement('div');
                            nav.className =
                                'absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2';
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

            function updateVariant(productId, selectedColor, selectedSize) {
                // Find the product variants with the selected color and size
                var variants = @json($products->pluck('variants')->flatten()->groupBy('product_id'));
                var productVariants = variants[productId];
                if (!productVariants) {
                    return;
                }
                var variant = productVariants.find(variant => variant.color === selectedColor && variant.size === selectedSize);
                if (variant) {

                    // Format price without decimal places and with comma separator
                    var formattedPrice = '₱' + variant.price.toLocaleString('en-US', {
                        maximumFractionDigits: 0
                    });

                    // Update price
                    document.getElementById('price_' + productId).textContent = formattedPrice;

                    // Update images
                    var carousel = document.getElementById('carousel_' + productId);
                    carousel.innerHTML = ''; // Clear existing images
                    variant.images.forEach((image, index) => {
                        var slide = document.createElement('div');
                        slide.id = 'slide' + productId + '-' + variant.id + '-' + (index + 1);
                        slide.className = 'carousel-item relative w-full h-full flex justify-center items-center ' + (
                            index === 0 ? 'active' : '');
                        var img = document.createElement('img');
                        img.src = '{{ asset('storage/') }}' + '/' + image.path;
                        img.alt = 'Product Image';
                        img.className = 'w-[250px] h-[300px] object-contain py-3';
                        slide.appendChild(img);
                        // Navigation buttons
                        var nav = document.createElement('div');
                        nav.className =
                            'absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2';
                        var prevBtn = document.createElement('a');
                        prevBtn.className = 'btn btn-circle';
                        prevBtn.href = '#slide' + productId + '-' + variant.id + '-' + (index > 0 ? index : variant
                            .images.length);
                        prevBtn.textContent = '❮';
                        var nextBtn = document.createElement('a');
                        nextBtn.className = 'btn btn-circle';
                        nextBtn.href = '#slide' + productId + '-' + variant.id + '-' + (index < variant.images.length -
                            1 ? index + 2 : 1);
                        nextBtn.textContent = '❯';
                        nav.appendChild(prevBtn);
                        nav.appendChild(nextBtn);
                        slide.appendChild(nav);
                        carousel.appendChild(slide);
                    });
                }
                return variant; // Return the variant object if found
            }

            const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};

            function addToCart(productId, variantId) {
                // Check if the user is logged in
                if (!isAuthenticated) {
                    // Redirect the user to the login route
                    window.location.href = '{{ route('register') }}';
                    return; // Stop further execution of the function
                }

                // Make an AJAX request to add the item to the cart
                fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            variant_id: variantId
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
                    filterProducts('', button);
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
                        productList.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(product => {
                                const buttonHtml = `
                 <button
    class="w-full h-auto p-4 sm:p-6 lg:p-8 rounded-lg shadow-lg flex flex-col items-center justify-between bg-white hover:bg-gray-100 transition-transform duration-300 transform hover:scale-105 mt-3"
    onclick="window.openModal(${product.id})" id="product_${product.id}">
    <div class="flex flex-col w-full h-full justify-between">
        <div class="flex flex-col items-center text-center w-full">
            <h2 class="text-base sm:text-lg lg:text-xl font-bold tracking-wider uppercase text-gray-800 mb-2"
                style="font-family: 'Roboto', sans-serif;">
                ${product.brand} ${product.name}
            </h2>
            <img src="/storage/${product.variants[0].images[0].path}" alt="${product.name}" 
                class="w-[120px] sm:w-[150px] lg:w-[180px] h-[160px] sm:h-[200px] lg:h-[240px] object-contain mb-4 rounded-md">
        </div>
        <p id="price_${product.id}" class="text-base sm:text-lg lg:text-xl font-light text-gray-900 mt-2"
            style="font-family: 'Roboto', sans-serif;">
            ₱${product.variants[0].price.toLocaleString('en-US', { maximumFractionDigits: 0 })}
        </p>
    </div>
</button>



              <!-- Modal structure for the filtered product -->
<dialog id="modal_${product.id}" class="modal">
    <div class="modal-box w-full h-screen max-h-screen lg:max-w-5xl lg:h-[80vh] p-4 overflow-y-auto flex items-center justify-center rounded-none xl:rounded-lg">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-50">✕</button>
        </form>

           <div id="alert_${product.id}"
                        class="alert hidden absolute top-4 left-1/2 transform -translate-x-1/2 w-[80%] text-center rounded-md py-4 px-6 shadow-lg text-gray-800 text-2xl font-bold transition ease-in-out duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2 stroke-current"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span id="alert-message_${product.id}"></span>
                    </div>
        
        <div class="card w-full h-full flex flex-col lg:flex-row mx-auto items-center lg:items-center">
            <div class="flex w-full lg:w-1/2 h-full flex-col justify-between p-4">
                <div class="flex flex-col items-center justify-center w-full h-full">
                    <h2 class="text-lg sm:text-xl lg:text-2xl font-bold tracking-wide text-gray-800 uppercase text-center pt-5" style="font-family: 'Roboto', sans-serif;">
                        ${product.brand} ${product.name}
                    </h2>
                    <div class="carousel w-full no-touch-scroll" id="carousel_${product.id}">
                        ${product.variants.map((variant, variantIndex) => variant.images.map((image, imageIndex) => `
                                                                                                                                                                                                                                                                                                                                                    <div id="slide${product.id}-${variant.id}-${imageIndex + 1}" class="carousel-item relative w-full h-full flex justify-center items-center ${imageIndex === 0 ? 'active' : ''}">
                                                                                                                                                                                                                                                                                                                                                        <img src="/storage/${image.path}" alt="${product.name}" class="w-[150px] h-[200px] md:w-[200px] md:h-[250px] lg:w-[300px] lg:h-[400px] object-contain py-3">
                                                                                                                                                                                                                                                                                                                                                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                                                                                                                                                                                                                                                                                                                                            ${imageIndex > 0 ? `<a href="#slide${product.id}-${variant.id}-${imageIndex}" class="btn btn-circle bg-gray-800 text-white">❮</a>` : `<a class="btn btn-circle bg-gray-300 text-gray-600 cursor-not-allowed">❮</a>`}
                                                                                                                                                                                                                                                                                                                                                            ${imageIndex < variant.images.length - 1 ? `<a href="#slide${product.id}-${variant.id}-${imageIndex + 2}" class="btn btn-circle bg-gray-800 text-white">❯</a>` : `<a class="btn btn-circle bg-gray-300 text-gray-600 cursor-not-allowed">❯</a>`}
                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                `).join('')).join('')}
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-1/2 flex flex-col justify-center p-4 flex-1">
                <div class="card-body flex flex-col justify-between space-y-5 flex-1">
                    <div>
                        <label for="color_${product.id}" class="block text-sm font-medium text-gray-700">Color</label>
                        <select id="color_${product.id}" name="color" onchange="window.handleColorChange(${product.id}, this.value)" class="mt-2 block w-full px-4 py-2 text-base border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md cursor-pointer transition duration-200">
                            ${[...new Set(product.variants.map(variant => variant.color))].map(color => `<option value="${color}">${color}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label for="size_${product.id}" class="block text-sm font-medium text-gray-700">Size</label>
                        <select id="size_${product.id}" name="size" onchange="window.handleSizeChange(${product.id}, this.value)" class="mt-2 block w-full px-4 py-2 text-base border border-gray-300 focus:outline-none focus:border-gray-500 rounded-md transition duration-200">
                            ${[...new Set(product.variants.map(variant => variant.size))].map(size => `<option value="${size}">${size}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <p id="modal_price_${product.id}" class="text-lg font-semibold text-gray-900">
                            ₱${product.variants[0].price.toLocaleString('en-US', { maximumFractionDigits: 0 })}
                        </p>
                    </div>
                    <div class="mt-6">
                        <button type="button" class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-300" onclick="window.handleAddToCart(${product.id})">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</dialog>


                `;
                                productList.insertAdjacentHTML('beforeend', buttonHtml);
                            });

                            // Attach event listeners after the product list is updated
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
                const variants = product.variants.filter(variant => variant.color === selectedColor && variant.size ===
                    selectedSize);
                if (variants.length === 0) {
                    return null;
                }
                const selectedVariant = variants[0];
                const formattedPrice = '₱' + selectedVariant.price.toLocaleString('en-US', {
                    maximumFractionDigits: 0
                });
                const priceElement = document.getElementById(`modal_price_${productId}`);
                if (priceElement) {
                    priceElement.textContent = formattedPrice;
                }
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
                const selectedColor = document.getElementById(`color_${productId}`).value;
                const selectedSize = document.getElementById(`size_${productId}`).value;
                const variant = updateVariants(productId, selectedColor, selectedSize);
                if (!variant) {
                    console.error("Variant not found for the selected options");
                    return;
                }
                addToCart(productId, variant.id);
            };
        </script>
    </div>
@endunless
