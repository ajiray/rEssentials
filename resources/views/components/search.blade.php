<!-- search.blade.php -->
<div id="searchContainer" class="flex items-center justify-center bg-gray-100 p-2 rounded-lg shadow-md">
    <input type="text" id="searchInput" name="query" placeholder="Search products by name, color, size, brand..."
        class="flex-1 px-4 py-2 bg-transparent border-none focus:outline-none text-gray-800 placeholder-gray-500"
        autocomplete="off">
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    let debounceTimer;

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        let debounceTimer;

        searchInput.addEventListener('keyup', function(event) {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            // Disable active category filter
            clearActiveCategory();

            if (query.length >= 1) {
                debounceTimer = setTimeout(() => {
                    fetchProducts(query);
                }, 300); // Adjust debounce delay as needed
            } else if (query === '') {
                fetchProducts('');
            }
        });

        // Function to clear the active category filter
        function clearActiveCategory() {
            const activeButton = document.querySelector('.filter-btn.bg-velvet.text-white');
            if (activeButton) {
                activeButton.classList.remove('bg-velvet', 'text-white');
                activeButton.classList.add('bg-white', 'text-velvet');
            }
        }
    });



    function fetchProducts(query) {
        if (query.trim() === '') { // If the query is empty, fetch all products
            fetch(`/search?query=${query}`)
                .then(response => response.json())
                .then(products => {
                    updateWelcomeView(products);
                    if (products.length === 0) {
                        displayNoResultsMessage();
                    }
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                });
        } else { // Otherwise, fetch products based on the search query
            fetch(`/search?query=${query}`)
                .then(response => response.json())
                .then(products => {
                    updateWelcomeView(products);
                    if (products.length === 0) {
                        displayNoResultsMessage();
                    }
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                });
        }
    }

    function displayNoResultsMessage() {
        const shopContainer = document.querySelector('.grid');
        shopContainer.innerHTML =
            '<div class="col-span-full flex items-center justify-center h-full w-full"><p class="text-center text-5xl py-12">No matches found</p></div>';
    }


    function updateWelcomeView(products) {
        const shopContainer = document.querySelector('.grid');

        // Update the productsData with the fetched products
        productsData = products;

        // Clearing container before appending new products
        shopContainer.innerHTML = '';

        if (products.length > 0) {
            products.forEach(product => {
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
                            Price: ₱${product.variants[0].price.toLocaleString('en-US', { maximumFractionDigits: 0 })}
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

                shopContainer.insertAdjacentHTML('beforeend', buttonHtml);
            });

            // Attach event listeners after updating the product list
            attachEventListeners();
        } else {
            shopContainer.innerHTML = `
            <div class="w-full h-64 flex justify-center items-center py-10">
                <p class="text-center text-gray-500 text-xl">No products found for the selected category.</p>
            </div>
        `;
        }
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

    function isLoggedIn() {
        return {{ Auth::check() ? 'true' : 'false' }};
    }

    function getProductColors(variants) {
        const colors = variants.map(variant => variant.color);
        return [...new Set(colors)];
    }

    function getProductSizes(variants, selectedColor) {
        const sizes = variants.filter(variant => variant.color === selectedColor).map(variant => variant.size);
        return [...new Set(sizes)];
    }

    function getAvailableSizes(productId, selectedColor) {
        const product = productsData.find(product => product.id === productId);
        if (!product) {
            return [];
        }
        return getProductSizes(product.variants, selectedColor);
    }

    window.handleColorChange = function(productId, selectedColor) {
        const sizeDropdown = document.getElementById(`size_${productId}`);
        const product = productsData.find(product => product.id === productId);
        if (!product) {
            console.error("Product not found");
            return;
        }
        const availableSizes = getAvailableSizes(productId, selectedColor);
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
        const mainPriceElement = document.getElementById(`price_${productId}`); // Main price element

        if (priceElement) {
            priceElement.textContent = 'Price: ' + formattedPrice;
        }
        if (mainPriceElement) { // Update main price element
            mainPriceElement.textContent = 'Price: ' + formattedPrice;
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

    window.getSelectedVariant = function(productId) {
        const selectedColor = document.getElementById(`color_${productId}`).getAttribute('data-selected-color');
        const selectedSize = document.getElementById(`size_${productId}`).value;
        return updateVariants(productId, selectedColor, selectedSize);
    };

    window.addToCart = function(productId, variantId) {
        // Check if the user is logged in
        if (!isLoggedIn()) {
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
</script>
