<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <title>Checkout</title>
    <link rel="stylesheet" href=
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
</head>
<style>
    #imageModal {
        z-index: 50;
    }

    #imageModal img {
        max-width: 90%;
        max-height: 90%;
    }

    #imageModal .relative {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #imageModal button {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
        margin: 0;
        font-size: 1.5rem;
        line-height: 1;
    }

    #imageModal button:hover {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 50%;
    }
</style>

<body class="w-full h-screen">
    <form method="POST" action="{{ route('cart.payment') }}" enctype="multipart/form-data">
        @csrf
        <section class="checkout-section py-6 px-4 lg:py-12 lg:px-8">
            <div class="checkout-container max-w-md mx-auto lg:max-w-4xl">
                <div class="relative flex items-center justify-center mb-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-circle btn-ghost absolute left-0">
                        <i class="fa-solid fa-angles-left text-sm lg:text-xl"></i>
                    </a>
                    <h2 class="text-2xl lg:text-4xl font-semibold tracking-wide">Order Summary</h2>
                </div>

                <hr class="border-t border-gray-300 mb-4">

                <!-- Items in the cart -->
                <div class="order-items mb-6 lg:mb-12">
                    <h3 class="text-lg lg:text-2xl font-semibold mb-2 text-center">Items in Your Cart</h3>
                    <ul>
                        @foreach ($cartItems as $cartItem)
                            <li class="pb-4 mb-4 flex items-center lg:pb-6 lg:mb-6">
                                <img src="/storage/{{ $cartItem->variant->images->first()->path }}"
                                    alt="{{ $cartItem->product->name }}"
                                    class="w-20 h-20 lg:w-32 lg:h-32 rounded-md object-cover mr-3 lg:mr-6">
                                <div>
                                    <h4 class="font-semibold text-gray-800 text-base lg:text-xl">
                                        {{ $cartItem->product->brand }}
                                        {{ $cartItem->product->name }}</h4>
                                    <p class="text-gray-600 text-sm lg:text-lg"><b>Price:</b>
                                        ₱{{ number_format($cartItem->variant->price, 0) }}</p>
                                    <p class="text-gray-600 text-sm lg:text-lg"><b>Color:</b>
                                        {{ $cartItem->variant->color }}</p>
                                    <p class="text-gray-600 text-sm lg:text-lg"><b>Size:</b>
                                        {{ $cartItem->variant->size }}</p>
                                    <p class="text-gray-600 text-sm lg:text-lg"><b>Quantity:</b>
                                        {{ $cartItem->quantity }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Order Total -->
                <div class="order-total mb-6 lg:mb-12 bg-gray-100 text-gray-800 py-2 lg:py-4 rounded-md shadow-sm">
                    <p class="text-center text-lg lg:text-2xl">Total Amount: ₱{{ number_format($totalAmount, 0, ',') }}
                    </p>
                </div>

                <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
                <hr class="border-t border-gray-300 mb-4">

                <!-- Payment Method -->
                <div class="payment-method mb-6 lg:mb-12">
                    <h3 class="font-semibold mb-2 text-center text-lg lg:text-2xl">Payment Method</h3>
                    <p class="text-gray-800 mb-4 text-center lg:text-xl">We're working on adding more payment options
                        for your
                        convenience. For now, please choose your preferred payment method:</p>
                    <div class="flex justify-center flex-col space-y-3 lg:flex-row lg:space-y-0 lg:space-x-8">
                        <div class="flex flex-col items-center">
                            <img src="{{ asset('images/gcash.png') }}" alt="GCash"
                                class="w-40 h-52 lg:w-48 lg:h-60 mb-2 cursor-pointer" onclick="showImageModal(this)">
                            <label for="gcash" class="text-gray-800 text-sm lg:text-lg">GCash (09999999999)</label>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="{{ asset('images/unionbank.png') }}" alt="UnionBank"
                                class="w-40 h-52 lg:w-48 lg:h-60 mb-2 cursor-pointer" onclick="showImageModal(this)">
                            <label for="unionbank" class="text-gray-800 text-sm lg:text-lg">UnionBank
                                (123123123123)</label>
                        </div>
                    </div>

                    <!-- Image Modal -->
                    <div id="imageModal"
                        class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden">
                        <div class="relative">
                            <img id="modalImage" src="" alt="Modal Image" class="max-w-full max-h-full">
                            <button type="button"
                                class="absolute top-0 right-2 text-white text-3xl bg-transparent border-none cursor-pointer"
                                onclick="closeImageModal()">✕</button>
                        </div>
                    </div>

                    <!-- Payment Receipt Upload -->
                    <div class="payment-receipt mt-6 lg:mt-12">
                        <h3 class="font-semibold mb-2 text-center text-lg lg:text-2xl">Upload Payment Receipt</h3>
                        <label for="payment_receipt" class="block text-gray-800 text-sm lg:text-lg mb-2">Payment Receipt
                            (required)</label>

                        <input type="file" id="payment_receipt" name="payment_receipt" accept="image/*"
                            class="mb-4 file-input file-input-bordered w-full max-w-xs lg:max-w-md" required />
                        <p class="text-xs text-gray-600 lg:text-sm">Please ensure that your payment receipt is clear and
                            legible.
                            Protect your receipt to prevent unauthorized access.</p>
                    </div>
                </div>

                <hr class="border-t border-gray-300 mb-4">

                <!-- Shipping Address -->
                <div class="shipping-address mb-6 lg:mb-12">
                    <h3 class="text-lg font-semibold mb-2 text-center lg:text-2xl">Shipping Address</h3>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="radio" id="use_current_address" name="address_option" value="current_address"
                                checked class="text-indigo-500 border-gray-300 focus:ring-indigo-500">
                            <label for="use_current_address" class="text-gray-800 text-sm lg:text-lg">Use Current
                                Address</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" id="use_other_address" name="address_option" value="other_address"
                                class="text-indigo-500 border-gray-300 focus:ring-indigo-500">
                            <label for="use_other_address" class="text-gray-800 text-sm lg:text-lg">Use Other
                                Address</label>
                        </div>

                        <!-- Current Address -->
                        <div id="current_address_fields" class="flex flex-col space-y-2 mt-2">
                            <x-input-label for="fullname" :value="__('Full Name')" />
                            <x-text-input id="fullname" name="fullname" :value="$userAddress['name']" readonly
                                class="w-full border-gray-300 rounded-md px-3 py-2 bg-gray-100 lg:px-4 lg:py-3" />
                            <x-input-label for="street_address" :value="__('Address')" />
                            <x-text-input id="street_address" name="street_address" :value="$userAddress['street_address']" readonly
                                class="w-full border-gray-300 rounded-md px-3 py-2 bg-gray-100 lg:px-4 lg:py-3" />
                            <x-input-label for="city" :value="__('City')" />
                            <x-text-input id="city" name="city" :value="$userAddress['city']" readonly
                                class="w-full border-gray-300 rounded-md px-3 py-2 bg-gray-100 lg:px-4 lg:py-3" />
                            <x-input-label for="province" :value="__('Province')" />
                            <x-text-input id="province" name="province" :value="$userAddress['province']" readonly
                                class="w-full border-gray-300 rounded-md px-3 py-2 bg-gray-100 lg:px-4 lg:py-3" />
                            <x-input-label for="postal_code" :value="__('Postal Code')" />
                            <x-text-input id="postal_code" name="postal_code" :value="$userAddress['postal_code']" readonly
                                class="w-full border-gray-300 rounded-md px-3 py-2 bg-gray-100 lg:px-4 lg:py-3" />
                            <x-input-label for="phone_number" :value="__('Phone Number')" />
                            <x-text-input id="phone_number" name="phone_number" :value="$userAddress['phone_number']" readonly
                                class="w-full border-gray-300 rounded-md px-3 py-2 bg-gray-100 lg:px-4 lg:py-3" />
                        </div>

                        <!-- Other Address -->
                        <div id="other_address_fields" style="display: none;" class="flex flex-col space-y-2 mt-2">
                            <x-input-label for="fullname_other" :value="__('Full Name')" />
                            <x-text-input id="fullname_other" name="fullname_other"
                                class="w-full border-gray-300 rounded-md px-3 py-2 lg:px-4 lg:py-3" />
                            <x-input-label for="address_other" :value="__('Address')" />
                            <x-text-input id="address_other" name="address_other"
                                class="w-full border-gray-300 rounded-md px-3 py-2 lg:px-4 lg:py-3" />
                            <x-input-label for="city_other" :value="__('City')" />
                            <x-text-input id="city_other" name="city_other"
                                class="w-full border-gray-300 rounded-md px-3 py-2 lg:px-4 lg:py-3" />
                            <x-input-label for="province_other" :value="__('Province')" />
                            <x-text-input id="province_other" name="province_other"
                                class="w-full border-gray-300 rounded-md px-3 py-2 lg:px-4 lg:py-3" />
                            <x-input-label for="postal_code_other" :value="__('Postal Code')" />
                            <x-text-input id="postal_code_other" name="postal_code_other"
                                class="w-full border-gray-300 rounded-md px-3 py-2 lg:px-4 lg:py-3" />
                            <x-input-label for="phone_number_other" :value="__('Phone Number')" />
                            <x-text-input id="phone_number_other" name="phone_number_other"
                                class="w-full border-gray-300 rounded-md px-3 py-2 lg:px-4 lg:py-3" />
                        </div>
                    </div>
                </div>

                <!-- Shipping Procedure -->
                <div class="shipping-procedure mb-6 lg:mb-12">
                    <h3 class="text-lg font-semibold mb-2 lg:text-2xl">Shipping Procedure</h3>
                    <p class="text-gray-800 mb-2 lg:text-lg">Enter shipping instructions or additional information
                        here:</p>
                    <textarea id="shipping_instructions" name="shipping_instructions" rows="4"
                        class="w-full border-gray-300 rounded-md px-3 py-2 border-2 lg:px-4 lg:py-3" required></textarea>
                </div>

                <!-- Checkout Button -->
                <button id="checkoutButton"
                    class="btn w-full px-4 py-3 border border-none rounded-md shadow-sm text-base font-medium text-velvet hover:text-white bg-green hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-300 lg:text-lg">Order</button>
            </div>
        </section>
    </form>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const useCurrentAddress = document.getElementById('use_current_address');
            const useOtherAddress = document.getElementById('use_other_address');
            const currentAddressFields = document.getElementById('current_address_fields');
            const otherAddressFields = document.getElementById('other_address_fields');

            // Function to show or hide address fields based on selection
            function toggleAddressFields(currentAddressSelected) {
                if (currentAddressSelected) {
                    currentAddressFields.style.display = 'block';
                    otherAddressFields.style.display = 'none';
                } else {
                    currentAddressFields.style.display = 'none';
                    otherAddressFields.style.display = 'block';
                }
            }

            // Initial setup based on default selection
            toggleAddressFields(useCurrentAddress.checked);

            // Event listener for when the user selects current address option
            useCurrentAddress.addEventListener('change', function() {
                toggleAddressFields(this.checked);
            });

            // Event listener for when the user selects other address option
            useOtherAddress.addEventListener('change', function() {
                toggleAddressFields(!this.checked);
            });
        });

        function showImageModal(element) {
            var modal = document.getElementById('imageModal');
            var modalImg = document.getElementById('modalImage');
            modalImg.src = element.src;
            modal.classList.remove('hidden');
        }

        function closeImageModal() {
            var modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
        }

        document.getElementById('imageModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeImageModal();
            }
        });
    </script>
</body>




</html>
