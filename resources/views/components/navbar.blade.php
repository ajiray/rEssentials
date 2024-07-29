<div class="navbar absolute top-0 z-50 flex justify-between items-center w-full p-4">
    <div class="flex items-center">
        <a href="#"><img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20"></a>
    </div>
    <div class="hidden md:flex space-x-5 items-center justify-center">
        <a href="https://instagram.com" target="_blank" class="text-pink-600 hover:text-pink-700">
            <i class="fa-brands fa-instagram text-3xl"></i>
        </a>
        <a href="https://www.facebook.com/profile.php?id=61554777501734" target="_blank"
            class="text-blue-600 hover:text-blue-700">
            <i class="fa-brands fa-facebook text-3xl"></i>
        </a>
        <a href="https://viber.com" target="_blank" class="text-purple-600 hover:text-purple-700">
            <i class="fa-brands fa-viber text-3xl"></i>
        </a>
    </div>
    <div class="hidden md:flex items-center">
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle" onclick="my_modal_3.showModal()">
                <div class="indicator">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="badge badge-sm indicator-item" id="cartItemCount">{{ $cartItemsCount }}</span>
                </div>
            </div>
        </div>
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                    <img alt="Profile Picture" src="{{ asset(auth()->user()->profile_picture) }}" />
                </div>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                <li><a href="{{ route('profile.edit') }}">Profile</a></li>
                <li><a href="{{ route('checkOrders') }}">Orders</a></li>
                <li>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
    <div class="md:hidden flex items-center">
        <button id="burger-menu" class="btn btn-ghost btn-circle">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</div>

<div id="mobile-menu"
    class="fixed inset-0 z-50 bg-white transform translate-x-full transition-transform duration-300 ease-in-out">
    <button id="close-mobile-menu" class="absolute top-4 right-4 btn btn-ghost btn-circle">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <div class="flex flex-col items-center justify-center h-auto py-24 space-y-6 p-6">

        <div class="flex flex-col w-full justify-center items-center space-y-5 mt-10">
            <div class="flex w-2/3 justify-between items-center">
                <a href="https://instagram.com" target="_blank" class="text-pink-600 hover:text-pink-700">
                    <i class="fa-brands fa-instagram text-4xl"></i>
                </a>
                <a href="https://www.facebook.com/profile.php?id=61554777501734" target="_blank"
                    class="text-blue-600 hover:text-blue-700">
                    <i class="fa-brands fa-facebook text-4xl"></i>
                </a>
                <a href="https://viber.com" target="_blank" class="text-purple-600 hover:text-purple-700">
                    <i class="fa-brands fa-viber text-4xl"></i>
                </a>
            </div>
            <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-gray-300">
                <img alt="Profile Picture" src="{{ asset(auth()->user()->profile_picture) }}"
                    class="w-full h-full object-cover" />
            </div>
        </div>

        <div class="flex flex-col w-full justify-center items-center space-y-5">
            <button onclick="my_modal_3.showModal()" class="btn btn-ghost btn-circle">
                <div class="flex justify-center items-center">

                    <div class="indicator ml-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="badge badge-sm indicator-item"
                            id="mobileCartItemCount">{{ $cartItemsCount }}</span>

                    </div>

                </div>
            </button>
            <a href="{{ route('profile.edit') }}" class="text-2xl font-semibold">Profile</a>
            <a href="{{ route('checkOrders') }}" class="text-2xl font-semibold">Orders</a>
            <a href="#" class="text-2xl font-semibold"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

    </div>
</div>

<script>
    // Toggle mobile menu
    document.getElementById('burger-menu').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.remove('translate-x-full');
        document.getElementById('mobile-menu').classList.add('translate-x-0');
    });

    // Close mobile menu
    document.getElementById('close-mobile-menu').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.remove('translate-x-0');
        document.getElementById('mobile-menu').classList.add('translate-x-full');
    });

    // Function to update the cart count dynamically
    function updateCartCount(count) {
        const cartItemCount = document.getElementById('cartItemCount');
        const mobileCartItemCount = document.getElementById('mobileCartItemCount');
        if (cartItemCount) {
            cartItemCount.textContent = count;
        }
        if (mobileCartItemCount) {
            mobileCartItemCount.textContent = count;
        }
    }

    // Function to update the cart subtotal dynamically
    function updateCartSubtotal(subtotal) {
        const cartSubtotal = document.getElementById('cartSubtotal');
        if (cartSubtotal) {
            cartSubtotal.textContent = 'Subtotal: ₱' + subtotal.toLocaleString();
        }
    }

    // Function to send an AJAX request to update the cart count and subtotal
    function fetchCartData() {
        fetch("{{ route('cart.data') }}", {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateCartCount(data.cartItemsCount);
                updateCartSubtotal(data.cartSubtotal);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Call the function to fetch and update the cart count and subtotal on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetchCartData();
    });

    // Update the cart count and subtotal after receiving a notification from the shop component
    document.addEventListener('cartItemAdded', function(event) {
        fetchCartData();
    });
</script>
