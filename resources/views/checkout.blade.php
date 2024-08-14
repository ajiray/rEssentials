<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1.10.6/dayjs.min.js"></script>

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

<body class="w-full h-screen text-gray-800">
    <form method="POST" action="{{ route('cart.payment') }}" enctype="multipart/form-data">
        @csrf
        <section class="checkout-section py-6 px-4 lg:py-12 lg:px-8">
            <div class="checkout-container max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg lg:max-w-4xl">
                <div class="relative flex items-center justify-center mb-6">
                    <a href="{{ route('dashboard') }}" class="absolute left-0 text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-angles-left text-xl lg:text-2xl"></i>
                    </a>
                    <h2 class="text-2xl lg:text-4xl font-semibold text-gray-900 tracking-wide">Order Summary</h2>
                </div>

                <hr class="border-t border-gray-300 mb-6">

                <!-- Items in the cart -->
                <div class="order-items mb-8 lg:mb-12">
                    <h3 class="text-lg lg:text-2xl font-semibold mb-4 text-center text-gray-900">Items in Your Cart</h3>
                    <ul>
                        @foreach ($cartItems as $cartItem)
                            <li class="flex items-center pb-4 mb-4 border-b border-gray-200 lg:pb-6 lg:mb-6">
                                <img src="/storage/{{ $cartItem->variant->images->first()->path }}"
                                    alt="{{ $cartItem->product->name }}"
                                    class="w-20 h-20 lg:w-32 lg:h-32 rounded-md object-cover mr-4 lg:mr-6">
                                <div>
                                    <h4 class="font-semibold text-gray-800 text-base lg:text-xl">
                                        {{ $cartItem->product->brand }} {{ $cartItem->product->name }}
                                    </h4>
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
                <div class="order-total mb-8 lg:mb-12 bg-gray-100 text-gray-800 py-4 lg:py-6 rounded-md shadow-sm">
                    <p class="text-center text-lg lg:text-2xl font-medium">Total Amount:
                        ₱{{ number_format($totalAmount, 0, ',') }}</p>
                </div>

                <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
                <hr class="border-t border-gray-300 mb-6">

                <!-- Payment Method -->
                <div class="payment-method mb-8 lg:mb-12">
                    <h3 class="font-semibold mb-4 text-center text-lg lg:text-2xl text-gray-900">Payment Method</h3>
                    <p class="text-gray-700 mb-6 text-center lg:text-lg">We're working on adding more payment options
                        for your convenience. For now, please choose your preferred payment method:</p>
                    <div class="flex justify-center flex-col space-y-3 lg:flex-row lg:space-y-0 lg:space-x-8">
                        <div class="flex flex-col items-center">
                            <img src="{{ asset('images/gcash.png') }}" alt="GCash"
                                class="w-40 h-52 lg:w-48 lg:h-60 mb-2 cursor-pointer transform hover:scale-105 transition-transform duration-200"
                                onclick="showImageModal(this)">
                            <label for="gcash" class="text-gray-800 text-sm lg:text-lg">GCash (09999999999)</label>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="{{ asset('images/unionbank.png') }}" alt="UnionBank"
                                class="w-40 h-52 lg:w-48 lg:h-60 mb-2 cursor-pointer transform hover:scale-105 transition-transform duration-200"
                                onclick="showImageModal(this)">
                            <label for="unionbank" class="text-gray-800 text-sm lg:text-lg">UnionBank
                                (123123123123)</label>
                        </div>
                    </div>

                    <!-- Layaway Option -->
                    <div class="layaway-option mt-8 lg:mt-12">
                        <div class="flex items-center mb-4">
                            <input type="checkbox" id="layaway_toggle" name="layaway_toggle"
                                class="mr-2 h-4 w-4 text-emerald-500 border-gray-300 rounded focus:ring-emerald-500">
                            <label for="layaway_toggle" class="text-gray-800 text-sm lg:text-lg font-semibold">Activate
                                Layaway Plan</label>
                        </div>
                        <p id="layaway_message" class="text-red-600 text-sm lg:text-lg mb-4 hidden">You need at least
                            ₱3000 to qualify for the layaway option.</p>
                        <p class="text-gray-700 mb-6 text-center lg:text-lg">Reserve your items with a deposit and pay
                            over time. No interest charges! You will receive the item after you finish paying the total
                            amount. Layaway plans are non-refundable but can be canceled anytime. Payments are due on
                            the 15th and the end of the month. Failure to pay within this period will result in
                            automatic cancellation.</p>
                        <div id="layaway_details" class="hidden">
                            <label for="layaway_deposit" class="block text-gray-800 text-sm lg:text-lg mb-2">Deposit
                                Amount (required, minimum 20%)</label>
                            <input type="number" id="layaway_deposit" name="layaway_deposit"
                                min="{{ $totalAmount * 0.2 }}" max="{{ $totalAmount }}" step="0.01"
                                placeholder="Minimum ₱{{ number_format($totalAmount * 0.2, 2) }}"
                                class="mb-4 block w-full lg:max-w-md px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm lg:text-base" />
                            <p class="text-xs text-gray-600 lg:text-sm">Enter the amount you wish to pay as a deposit.
                                The remaining balance will be due in regular installments.</p>

                            <label for="layaway_duration"
                                class="block text-gray-800 text-sm lg:text-lg mb-2 mt-4">Payment Plan Duration</label>
                            <select id="layaway_duration" name="layaway_duration"
                                class="block w-full lg:max-w-md px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm lg:text-base">
                                <option value="1">1 Month</option>
                                <option value="2">2 Months</option>
                                <option value="3">3 Months</option>
                            </select>

                            <div id="payment_schedule" class="text-gray-800 text-sm lg:text-lg mt-6"></div>
                        </div>
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
                <div class="payment-receipt mt-8 lg:mt-12">
                    <h3 class="font-semibold mb-4 text-center text-lg lg:text-2xl text-gray-900">Upload Payment Receipt
                    </h3>
                    <label for="payment_receipt" class="block text-gray-800 text-sm lg:text-lg mb-2">Payment Receipt
                        (required)</label>

                    <input type="file" id="payment_receipt" name="payment_receipt" accept="image/*"
                        class="block w-full lg:max-w-md mb-4 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm lg:text-base"
                        required />
                    <p class="text-xs text-gray-600 lg:text-sm">Please ensure that your payment receipt is clear and
                        legible. Protect your receipt to prevent unauthorized access.</p>
                </div>

                <hr class="border-t border-gray-300 mb-6">

                <!-- Shipping Address -->
                <div class="shipping-address mb-8 lg:mb-12">
                    <h3 class="text-lg font-semibold mb-4 text-center lg:text-2xl text-gray-900">Shipping Address</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <input type="radio" id="use_current_address" name="address_option"
                                value="current_address" checked
                                class="text-emerald-500 border-gray-300 focus:ring-emerald-500">
                            <label for="use_current_address" class="text-gray-800 text-sm lg:text-lg">Use Current
                                Address</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" id="use_other_address" name="address_option" value="other_address"
                                class="text-emerald-500 border-gray-300 focus:ring-emerald-500">
                            <label for="use_other_address" class="text-gray-800 text-sm lg:text-lg">Use Other
                                Address</label>
                        </div>

                        <!-- Current Address -->
                        <div id="current_address_fields" class="flex flex-col space-y-2 mt-2">
                            <x-input-label for="fullname" :value="__('Full Name')" />
                            <x-text-input id="fullname" name="fullname" :value="$userAddress['name']" readonly
                                class="w-full border-gray-300 rounded-md px-4 py-2 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                            <x-input-label for="street_address" :value="__('Address')" />
                            <x-text-input id="street_address" name="street_address" :value="$userAddress['street_address']" readonly
                                class="w-full border-gray-300 rounded-md px-4 py-2 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                            <x-input-label for="city" :value="__('City')" />
                            <x-text-input id="city" name="city" :value="$userAddress['city']" readonly
                                class="w-full border-gray-300 rounded-md px-4 py-2 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                            <x-input-label for="province" :value="__('Province')" />
                            <x-text-input id="province" name="province" :value="$userAddress['province']" readonly
                                class="w-full border-gray-300 rounded-md px-4 py-2 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                            <x-input-label for="postal_code" :value="__('Postal Code')" />
                            <x-text-input id="postal_code" name="postal_code" :value="$userAddress['postal_code']" readonly
                                class="w-full border-gray-300 rounded-md px-4 py-2 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                            <x-input-label for="phone_number" :value="__('Phone Number')" />
                            <x-text-input id="phone_number" name="phone_number" :value="$userAddress['phone_number']" readonly
                                class="w-full border-gray-300 rounded-md px-4 py-2 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                        </div>

                        <!-- Other Address -->
                        <div id="other_address_fields" style="display: none;" class="flex flex-col space-y-2 mt-2">
                            <x-input-label for="fullname_other" :value="__('Full Name')" />
                            <x-text-input id="fullname_other" name="fullname_other"
                                class="w-full border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                            <x-input-label for="address_other" :value="__('Address')" />
                            <x-text-input id="address_other" name="address_other"
                                class="w-full border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                            <x-input-label for="city_other" :value="__('City')" />
                            <x-text-input id="city_other" name="city_other"
                                class="w-full border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                            <x-input-label for="province_other" :value="__('Province')" />
                            <x-text-input id="province_other" name="province_other"
                                class="w-full border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                            <x-input-label for="postal_code_other" :value="__('Postal Code')" />
                            <x-text-input id="postal_code_other" name="postal_code_other"
                                class="w-full border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                            <x-input-label for="phone_number_other" :value="__('Phone Number')" />
                            <x-text-input id="phone_number_other" name="phone_number_other"
                                class="w-full border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                        </div>
                    </div>
                </div>

                <!-- Shipping Procedure -->
                <div class="shipping-procedure mb-8 lg:mb-12">
                    <h3 class="text-lg font-semibold mb-4 lg:text-2xl text-gray-900">Shipping Procedure</h3>
                    <p class="text-gray-700 mb-2 lg:text-lg">Enter shipping instructions or additional information
                        here:</p>
                    <textarea id="shipping_instructions" name="shipping_instructions" rows="4" required
                        class="w-full border-gray-300rounded-md px-4 py-2 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>

                <!-- Checkout Button -->
                <button id="checkoutButton"
                    class="w-full px-4 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-300 lg:text-lg">Place
                    Order</button>
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

            // Toggle layaway details
            const layawayToggle = document.getElementById('layaway_toggle');
            const layawayDetails = document.getElementById('layaway_details');

            layawayToggle.addEventListener('change', function() {
                if (this.checked) {
                    layawayDetails.style.display = 'block';
                } else {
                    layawayDetails.style.display = 'none';
                }
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

        document.addEventListener('DOMContentLoaded', function() {
            const useCurrentAddress = document.getElementById('use_current_address');
            const useOtherAddress = document.getElementById('use_other_address');
            const currentAddressFields = document.getElementById('current_address_fields');
            const otherAddressFields = document.getElementById('other_address_fields');
            const layawayToggle = document.getElementById('layaway_toggle');
            const layawayDetails = document.getElementById('layaway_details');
            const layawayDepositInput = document.getElementById('layaway_deposit');
            const layawayDurationSelect = document.getElementById('layaway_duration');
            const paymentScheduleDiv = document.getElementById('payment_schedule');
            const totalAmount = {{ $totalAmount }};
            const minDeposit = totalAmount * 0.20;

            const layawayMessage = document.getElementById('layaway_message');

            // Check if total amount qualifies for layaway option
            if (totalAmount >= 3000) {
                layawayToggle.disabled = false;
                layawayMessage.style.display = 'none';
            } else {
                layawayToggle.disabled = true;
                layawayMessage.style.display = 'block';
            }

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

            // Toggle layaway details
            layawayToggle.addEventListener('change', function() {
                if (this.checked) {
                    layawayDetails.style.display = 'block';
                    layawayDepositInput.setAttribute('required', 'required');
                } else {
                    layawayDetails.style.display = 'none';
                    layawayDepositInput.removeAttribute('required');
                }
            });

            layawayDepositInput.addEventListener('input', function() {
                if (this.value < minDeposit) {
                    this.setCustomValidity(`Minimum deposit is ₱${minDeposit.toFixed(2)}`);
                } else {
                    this.setCustomValidity('');
                }
            });

            layawayDurationSelect.addEventListener('change', updatePaymentSchedule);
            layawayDepositInput.addEventListener('input', updatePaymentSchedule);

            function updatePaymentSchedule() {
                const depositAmount = parseFloat(layawayDepositInput.value) || 0;
                const duration = parseInt(layawayDurationSelect.value);
                const remainingAmount = totalAmount - depositAmount;
                const remainingPayments = duration * 2; // 2 payments per month
                const installmentAmount = remainingAmount / remainingPayments;

                let scheduleHtml = `<p>Total amount: ₱${totalAmount.toFixed(2)}</p>`;
                scheduleHtml += `<p>Deposit amount: ₱${depositAmount.toFixed(2)}</p>`;
                scheduleHtml += `<p>Remaining amount: ₱${remainingAmount.toFixed(2)}</p>`;
                scheduleHtml += `<p>You will pay ₱${installmentAmount.toFixed(2)} on the following dates:</p>`;

                const today = dayjs();
                let paymentDate;

                // Determine the first payment date
                if (today.date() <= 15) {
                    // If today is between 1st and 15th, first payment is at the end of the current month
                    paymentDate = today.endOf('month');
                } else {
                    // If today is after 15th, first payment is on the 15th of the next month
                    paymentDate = today.add(1, 'month').date(15);
                }

                // Add the first payment date to the schedule
                scheduleHtml += `<p>${paymentDate.format('MMMM D, YYYY')}</p>`;

                // Calculate subsequent payment dates
                for (let i = 1; i < remainingPayments; i++) {
                    if (paymentDate.date() === 15) {
                        // Move to the last day of the current month
                        paymentDate = paymentDate.endOf('month');
                    } else {
                        // Move to the 15th of the next month
                        paymentDate = paymentDate.add(1, 'month').date(15);
                    }

                    scheduleHtml += `<p>${paymentDate.format('MMMM D, YYYY')}</p>`;
                }

                paymentScheduleDiv.innerHTML = scheduleHtml;
            }





        });
    </script>
</body>

</html>
