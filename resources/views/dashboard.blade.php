<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href=
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
    <title>{{ config('app.name', 'Laravel') }}</title>

</head>

<style>
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

<body class="w-full h-screen">

    <dialog id="my_modal_3" class="modal w-full">
        <div class="modal-box p-4 bg-white shadow-md rounded-none max-h-screen h-screen lg:max-h-[750px] lg:rounded-lg lg:w-1/2 flex flex-col"
            style="width: 100vw; height: 100vh; max-width: 825px;">
            <button id="closeModalButton" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>

            <form id="updateCartForm" action="{{ route('cart.updateAndCheckout') }}" method="POST"
                class="flex flex-col h-full">
                @csrf
                @method('PATCH')
                <h1 class="text-3xl text-center font-semibold tracking-wider pb-5 text-velvet">Cart</h1>

                <div id="cartItemsContainer" class="space-y-4 flex-grow overflow-y-auto overflow-x-hidden">
                    <!-- Cart items will be displayed here -->
                </div>
                <hr id="subtotalSeparator" class="border-t border-gray-300 my-4" style="display: none;">
                <div class="flex justify-center py-3">
                    <button type="submit"
                        class="btn btn-primary w-[95%] text-base font-medium text-gray-800 bg-green hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-700 transition ease-in-out duration-300 border-none"
                        id="checkoutButton">Checkout</button>
            </form>

            <script>
                document.getElementById('closeModalButton').addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent the default behavior of the button click event
                    document.getElementById('my_modal_3').close(); // Close the modal
                });

                document.addEventListener('DOMContentLoaded', function() {
                    fetchCartItems();
                });

                // Function to update cart items
                function updateCartItems(cartItems) {
    const cartItemsContainer = document.getElementById('cartItemsContainer');
    const checkoutButton = document.getElementById('checkoutButton');
    const subtotalSection = document.getElementById('subtotalSection');
    const subtotalSeparator = document.getElementById('subtotalSeparator');

    if (cartItemsContainer) {
        cartItemsContainer.innerHTML = ''; // Clear previous items

        if (cartItems.length === 0) {
            cartItemsContainer.innerHTML = '<p class="text-center text-gray-600">No item in the cart.</p>';
            if (checkoutButton) {
                checkoutButton.style.display = 'none'; // Hide the checkout button
            }
            if (subtotalSection) {
                subtotalSection.style.display = 'none'; // Hide subtotal section
            }
            if (subtotalSeparator) {
                subtotalSeparator.style.display = 'none'; // Hide the horizontal line
            }
        } else {
            cartItems.forEach(cartItem => {
                const itemDiv = document.createElement('div');
                // Access the related data
                const variant = cartItem.variant;
                const product = variant && variant.product;
                const image = variant && variant.images && variant.images.length > 0 ? variant.images[0].path : '';

                if (product) {
                    // Use the cartItem.quantity to set the current quantity
                    itemDiv.innerHTML = `
                        <div class="flex flex-col space-y-3 relative shadow-md p-3 rounded-md border-gray-100 border-2">
                            <div class="w-full flex items-center space-x-4 lg:space-x-6 xl:space-x-8">
                                <div class="w-24 h-24 overflow-hidden lg:w-32 lg:h-32 xl:w-40 xl:h-40">
                                    <img src="/storage/${image}" alt="${product.name}" class="w-full h-full object-contain">
                                </div>
                                <div class="text-sm lg:text-base xl:text-lg">
                                    <h3 class="font-semibold">${product.brand} ${product.name}</h3>
                                    <h3 class="text-gray-600"><b>Stocks:</b> ${variant.quantity}</h3>
                                    <h3 class="text-gray-600"><b>Color:</b> ${variant.color}</h3>
                                    <h3 class="text-gray-600"><b>Size:</b> ${variant.size}</h3>
                                    <p class="text-gray-600"><b>Price:</b> ₱${Math.floor(variant.price).toLocaleString()}</p>
                                </div>
                            </div>
                            <div class="w-full flex justify-between items-center mt-2 lg:mt-4 xl:mt-6">
                                <input type="hidden" name="cart_id[]" value="${cartItem.id}">
                                <div class="flex items-center space-x-2">
                                    <label for="quantity_${variant.id}" class="block text-gray-600">Quantity:</label>
                                    <button type="button" class="text-white bg-gray-400 rounded-full w-6 h-6 flex items-center justify-center lg:w-6 lg:h-6 xl:w-8 xl:h-8" onclick="decrementQuantity(${variant.id}, ${variant.quantity})">-</button>
                                    <!-- Set the initial value of the quantity input to cartItem.quantity -->
                                    <input type="number" id="quantity_${variant.id}" name="quantity[]" value="${cartItem.quantity}" class="w-16 border rounded-md text-center" min="1" max="${variant.quantity}" oninput="updateMaxQuantity(this, ${variant.quantity})" required>
                                    <button type="button" class="text-white bg-gray-400 rounded-full w-6 h-6 flex items-center justify-center lg:w-6 lg:h-6 xl:w-8 xl:h-8" onclick="incrementQuantity(${variant.id}, ${variant.quantity})">+</button>
                                </div>
                                <a class="absolute text-sm top-0 right-0 text-white bg-red-500 hover:bg-red-600 rounded-full px-1 cursor-pointer shadow-md transition duration-300 ease-in-out transform hover:scale-110" onclick="deleteCartItem(${cartItem.id})">
                                    <i class="fa-solid fa-minus text-sm"></i>
                                </a>
                            </div>
                        </div>
                    `;
                    cartItemsContainer.appendChild(itemDiv);
                } else {
                    console.error('Product not found for variant:', variant);
                }
            });

            if (subtotalSection) {
                subtotalSection.style.display = 'block'; // Show subtotal section
            }
            if (subtotalSeparator) {
                subtotalSeparator.style.display = 'block'; // Show the horizontal line
            }
            if (checkoutButton) {
                checkoutButton.style.display = 'block'; // Show the checkout button
            }
        }
    }
}

                // Fetch cart items function
                function fetchCartItems() {
                    fetch("{{ route('cart.items') }}")
                        .then(response => response.json())
                        .then(data => {
                            updateCartItems(data.cartItems);
                        })
                        .catch(error => {
                            console.error('Error fetching cart items:', error);
                        });
                }

                // Call fetch cart items on page load
                document.addEventListener('DOMContentLoaded', function() {
                    fetchCartItems();
                });

                // Add event listener for when an item is added to cart
                document.addEventListener('cartItemAdded', function(event) {
                    fetchCartItems();
                });

                function deleteCartItem(cartItemId) {
                    fetch("{{ route('cart.delete') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                cartItemId: cartItemId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            fetchCartItems(); // Fetch updated cart items after deletion
                            document.dispatchEvent(new Event('cartItemAdded'));
                        })
                        .catch(error => {
                            console.error('Error deleting cart item:', error);
                        });
                }

                function updateMaxQuantity(input, max) {
                    if (parseInt(input.value) <= 0 || isNaN(parseInt(input.value))) {
                        input.value = ''; // Clear the input if it's empty or 0
                    } else if (parseInt(input.value) > max) {
                        input.value = max; // Reset to max if input is greater than max
                    }
                }

                function decrementQuantity(productId, max) {
                    const quantityInput = document.getElementById(`quantity_${productId}`);
                    let value = parseInt(quantityInput.value);
                    if (value > 1) {
                        quantityInput.value = value - 1;
                    }
                }

                function incrementQuantity(productId, max) {
                    const quantityInput = document.getElementById(`quantity_${productId}`);
                    let value = parseInt(quantityInput.value);
                    if (value < max) {
                        quantityInput.value = value + 1;
                    }
                }

                function updateMaxQuantity(input, max) {
                    if (parseInt(input.value) <= 0 || isNaN(parseInt(input.value))) {
                        input.value = ''; // Clear the input if it's empty or 0
                    } else if (parseInt(input.value) > max) {
                        input.value = max; // Reset to max if input is greater than max
                    }
                }
            </script>
        </div>
    </dialog>







    <section class="w-full h-full">
        <x-navbar :cart-items-count="$cartItemsCount" />
        <x-hero />
    </section>

    <section class="second-section w-full h-auto min-h-full">

      
        <div>
            <x-shop :products="$products" />
        </div>


    </section>

    <div>
        <x-footer />
    </div>




    <script>
        function goToSecondSec() {
            const secondSec = document.querySelector('.second-section');
            if (secondSec) {
                secondSec.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    </script>
</body>

</html>
