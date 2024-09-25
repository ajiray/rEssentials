<x-app-layout>
    @if (session('success'))
        <div id="alert"
            class="alert alert-success fixed top-0 inset-x-0 z-50 opacity-0 transition-opacity duration-500 w-[50%] mx-auto mt-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <dialog id="my_modal_3" class="modal">
        <div
            class="modal-box bg-white rounded-none md:rounded-xl shadow-lg p-4 w-screen h-screen max-h-screen sm:max-h-screen md:h-auto md:max-h-[800px] md:w-screen md:max-w-xl lg:max-h-[800px] lg:max-w-2xl xl:max-w-3xl mx-auto relative overflow-y-auto">
            <form method="dialog" class="absolute top-2 right-2">
                <button id="closeModalButton"
                    class="btn btn-sm btn-circle btn-ghost text-gray-500 hover:text-gray-800">✕</button>
            </form>
            <h3 class="font-bold text-lg sm:text-xl md:text-2xl lg:text-3xl text-gray-800 text-center mb-4">Items</h3>
            <ul id="itemList" class="space-y-4"></ul>
        </div>
    </dialog>

    <!-- Layaway Modal -->
    <dialog id="layaway_modal" class="modal">
        <div
            class="modal-box bg-white rounded-none md:rounded-xl shadow-lg p-6 w-screen h-screen max-h-screen sm:max-h-screen md:h-auto md:max-h-[800px] md:w-screen md:max-w-xl lg:max-h-[800px] lg:max-w-2xl xl:max-w-3xl mx-auto relative overflow-y-auto">
            <form method="dialog" class="absolute top-4 right-4">
                <button id="closeLayawayModalButton"
                    class="btn btn-sm btn-circle btn-ghost text-gray-500 hover:text-gray-800"
                    onclick="clearPaymentAmount()">✕</button>
            </form>
            <h3 class="font-bold text-2xl sm:text-3xl md:text-4xl lg:text-5xl text-gray-900 text-center mb-6">Layaway
                Payments</h3>
            <div id="layawayInfo" class="text-center text-gray-700 mb-6">
                <p id="totalAmount" class="text-xl"></p>
                <p id="paymentProgress" class="text-lg"></p>
                <p id="amount_paid" class="text-lg"></p>
                <p id="remainingBalance" class="text-lg"></p>
                <p id="nextPaymentDue" class="text-lg"></p>
                <p id="nextPaymentAmount" class="text-lg"></p>
            </div>
            <ul id="layawayPaymentList" class="space-y-6 mb-6"></ul>
            <h3 class="font-bold text-2xl sm:text-3xl md:text-4xl lg:text-5xl text-gray-900 text-center mb-6">Payment
                Methods</h3>
            <div class="flex justify-center flex-col space-y-6 lg:flex-row lg:space-y-0 lg:space-x-10">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/gcash.png') }}" alt="GCash"
                        class="w-40 h-52 lg:w-48 lg:h-60 mb-4 cursor-pointer transform hover:scale-105 transition-transform duration-200"
                        onclick="showImageModal(this)">
                    <label class="text-gray-800 text-lg lg:text-xl">GCash (09999999999)</label>
                </div>
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/unionbank.png') }}" alt="UnionBank"
                        class="w-40 h-52 lg:w-48 lg:h-60 mb-4 cursor-pointer transform hover:scale-105 transition-transform duration-200"
                        onclick="showImageModal(this)">
                    <label class="text-gray-800 text-lg lg:text-xl">UnionBank (123123123123)</label>
                </div>
            </div>

            <h3 class="font-bold text-2xl sm:text-3xl md:text-4xl lg:text-5xl text-gray-900 text-center my-6">New
                Payment</h3>
            <input type="hidden" id="order_id" name="order_id">
            <div class="mb-6">
                <label for="payment_amount" class="block text-gray-800 text-lg lg:text-xl mb-4">Payment Amount</label>
                <input type="number" id="payment_amount" name="payment_amount" step="0.01"
                    class="file-input file-input-bordered w-full max-w-xs lg:max-w-md">

                <p id="paymentError" class="text-red-600 text-sm mt-2 hidden"></p> <!-- Error message container -->
            </div>

            <div id="paymentInfo" class="text-center text-gray-700 my-6"></div> <!-- Missing div added -->
            <div class="mb-6">
                <label for="payment_receipt" class="block text-gray-800 text-lg lg:text-xl mb-4">Upload Payment
                    Receipt</label>
                <input type="file" id="payment_receipt" name="payment_receipt" accept="image/*" required
                    class="file-input file-input-bordered w-full max-w-xs lg:max-w-md">

            </div>

            <button type="button" id="submitPaymentButton"
                class="btn w-full px-4 py-3 border border-none rounded-md shadow-sm text-base font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-300 lg:text-lg">Submit
                Payment</button>
        </div>
    </dialog>



    <!-- Image Modal -->
    <dialog id="imageModal" class="modal">
        <div
            class="modal-box bg-white rounded-none md:rounded-xl shadow-lg p-4 w-screen h-screen max-h-screen sm:max-h-screen md:h-auto md:max-h-[800px] md:w-screen md:max-w-xl lg:max-h-[800px] lg:max-w-2xl xl:max-w-3xl mx-auto relative flex items-center justify-center">
            <form method="dialog" class="absolute top-2 right-2">
                <button class="btn btn-sm btn-circle btn-ghost text-gray-500 hover:text-gray-800">✕</button>
            </form>
            <img id="modalImage" src="" alt="Image" class="max-w-full h-auto mx-auto">
        </div>
    </dialog>

    <!-- Refund Request Modal -->
<dialog id="refundModal" class="modal">
    <div class="modal-box bg-white rounded-xl shadow-lg p-6 relative">
        <form id="refundForm">
            <button id="closeRefundModal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            <h3 class="font-bold text-2xl text-center mb-4 text-gray-800">Refund Request</h3>

            <!-- Refund Reason -->
            <div class="mb-4">
                <label for="refundReason" class="block text-gray-800 text-lg mb-2">Reason for Refund</label>
                <textarea id="refundReason" name="refund_reason" required
                    class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    placeholder="Enter the reason for requesting a refund"></textarea>
            </div>

            <!-- Preferred Mode of Payment -->
              <!-- Payment Details (e.g. Account number) -->
            <div class="mb-4">
                <label for="paymentMethod" class="block text-gray-800 text-lg mb-2">Payment Method</label>
                <input type="text" id="paymentMethod" name="payment_method" required
                    class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    placeholder="Gcash, BDO, Unionbank, etc." />
            </div>

            <!-- Payment Details (e.g. Account number) -->
            <div class="mb-4">
                <label for="paymentDetails" class="block text-gray-800 text-lg mb-2">Payment Details</label>
                <input type="text" id="paymentDetails" name="payment_details" required
                    class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    placeholder="Account number - Account name" />
            </div>

            <!-- Refund Information -->
            <div class="bg-yellow-100 text-yellow-700 p-3 rounded-md shadow-md mb-6">
                <p class="text-md">
                    After submitting this request, the admin will review your refund. You will be notified once the decision is made.
                </p>
            </div>

            <!-- Hidden Input for Order ID -->
            <input type="hidden" id="refundOrderId" name="order_id" />

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full bg-red-600 text-white py-3 rounded-md shadow-lg hover:bg-red-700 transition duration-300 ease-in-out">
                Submit Refund Request
            </button>
        </form>
    </div>
</dialog>

<dialog id="refundImageModal" class="modal">
    <div class="modal-box bg-white rounded-md shadow-lg p-4">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <img id="refundImage" src="" alt="Refund Image" class="w-full h-auto">
    </div>
</dialog>


    <div class="py-12 hidden lg:block">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="p-6 sm:px-20 border-b border-marble relative">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-1 text-velvet hover:text-blue-300 absolute top-10 left-5">
                        <i class="fa-solid fa-angles-left text-xl"></i>
                        <span>Home</span>
                    </a>

                    <div class="text-5xl text-center text-velvet font-extrabold tracking-widest">
                        Your Orders
                    </div>
                </div>

                <!-- Table -->
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow-lg overflow-hidden border-b border-marble sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-500">
                                    <thead class="bg-velvet">
                                        <tr class="text-center text-blanc">

                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Order ID
                                            </th>
                                            <!-- Center align text in header row -->
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Order Date
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Status
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Shipping Method
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Tracking Number
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Payment
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Items
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-sm font-semibold uppercase tracking-widest">
                                                Refund
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 text-center">
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                                                    <div class="text-sm font-medium tracking-wide">
                                                        {{ $order->id }}</div>
                            </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                                <div class="text-sm font-medium tracking-wide">
                                    {{ date('F j, Y', strtotime($order->order_date)) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="px-4 py-2 rounded-full inline-block
                                    @if ($order->shipping_status == 'delivered') bg-emerald-100 text-emerald-600
                                    @elseif ($order->shipping_status == 'shipped') bg-blue-100 text-blue-600
                                    @elseif ($order->shipping_status == 'preparing') bg-amber-100 text-amber-600
                                    @elseif ($order->shipping_status == 'Declined') bg-red-100 text-red-600 @endif">
                                    {{ $order->shipping_status }}
                                </div>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                                <div class="text-sm font-medium tracking-wide">
                                    {{ $order->shipping_method }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                                <div class="text-sm font-medium tracking-wide">
                                    {{ $order->tracking_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                                <div class="text-sm font-medium tracking-wide">
                                    @if ($order->payment_method == 'layaway')
                                        <button onclick="viewLayawayPayments({{ $order->id }})"
                                            class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                            {{ $order->payment_method }}
                                        </button>
                                    @elseif ($order->payment_method == 'Cancelled')
                                        <span class="text-red-600 font-semibold">
                                            {{ $order->payment_method }}
                                        </span>
                                    @else
                                        <span class="text-emerald-600 font-semibold">
                                            {{ $order->payment_method }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="viewItems({{ $order->id }})"
                                    class="text-indigo-600 hover:text-indigo-800 font-semibold">View
                                    Items</button>
                            </td>
                            <td>
                                @if ($order->shipping_status == 'delivered')
                                    @if ($order->refund_status == 'requested')
                                        <p class="text-amber-600 font-semibold">Refund Requested</p>
                                    @elseif ($order->refund_status == 'processed')
                                        <button onclick="openRefundImage('{{ $order->refund_receipt }}')" 
                                            class="text-emerald-600 hover:text-emerald-800 font-semibold">
                                            Refunded
                                        </button>
                                    @else
                                        <button onclick="requestRefund({{ $order->id }})" 
                                            class="text-red-600 hover:text-red-800 font-semibold">
                                            Request Refund
                                        </button>
                                    @endif
                                @endif
                            </td>
                            </tr>
                            @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Table -->
        </div>
    </div>
    </div>

    <!-- Mobile View -->
    <div class="lg:hidden w-full h-full flex flex-col py-6 px-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-velvet hover:text-silk mb-4">
            <i class="fa-solid fa-angles-left text-xl"></i>
            <span>Home</span>
        </a>

        <div class="text-3xl text-center text-velvet mb-6 font-bold">
            Your Orders
        </div>

        @foreach ($orders as $order)
            <div class="border border-gray-300 rounded-lg p-4 mb-4 shadow-md bg-white">
                <div class="mb-2">
                    <span class="font-semibold text-velvet">Order ID:</span>
                    <span class="text-gray-700">{{ $order->id }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-semibold text-velvet">Order Date:</span>
                    <span class="text-gray-700">{{ date('F j, Y', strtotime($order->order_date)) }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-semibold text-velvet">Status:</span>
                    <span
                        class="px-2 py-1 rounded-full
                    @if ($order->shipping_status == 'delivered') bg-emerald-100 text-emerald-600
                    @elseif ($order->shipping_status == 'shipped') bg-blue-100 text-blue-600
                    @elseif ($order->shipping_status == 'preparing') bg-amber-100 text-amber-600
                    @elseif ($order->shipping_status == 'Declined') bg-red-100 text-red-600 @endif">
                        {{ $order->shipping_status }}
                    </span>

                </div>
                <div class="mb-2">
                    <span class="font-semibold text-velvet">Shipping Method:</span>
                    <span class="text-gray-700">{{ $order->shipping_method }}</span>
                </div>
                <div class="mb-2">
                    <span class="font-semibold text-velvet">Tracking Number:</span>
                    <span class="text-gray-700">{{ $order->tracking_number }}</span>
                </div>
                <div class="mb-2 flex">
                    <span class="font-semibold text-velvet">Payment:</span>
                    <span>
                        @if ($order->payment_method == 'layaway')
                            <button onclick="viewLayawayPayments({{ $order->id }})"
                                class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                {{ $order->payment_method }}
                            </button>
                        @elseif ($order->payment_method == 'Cancelled')
                            <span class="text-red-600 font-semibold">
                                {{ $order->payment_method }}
                            </span>
                        @else
                            <span class="text-emerald-600 font-semibold">
                                {{ $order->payment_method }}
                            </span>
                        @endif
                    </span>
                </div>
                <div>
                    <span class="font-semibold text-velvet">Refund:</span>
                    @if ($order->shipping_status == 'delivered')
                                    @if ($order->refund_status == 'requested')
                                        <p class="text-amber-600 font-semibold">Refund Requested</p>
                                    @elseif ($order->refund_status == 'processed')
                                        <button onclick="openRefundImage('{{ $order->refund_receipt }}')" 
                                            class="text-emerald-600 hover:text-emerald-800 font-semibold">
                                            Refunded
                                        </button>
                                    @else
                                        <button onclick="requestRefund({{ $order->id }})" 
                                            class="text-red-600 hover:text-red-800 font-semibold">
                                            Request Refund
                                        </button>
                                    @endif
                                @endif
                </div>

                <div class="mt-4">
                    <button onclick="viewItems({{ $order->id }})"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-800 transition duration-300">
                        View Items
                    </button>
                </div>
                
           
            </div>
        @endforeach
    </div>

    <script>
        function clearPaymentAmount() {
            const paymentAmountInput = document.getElementById('payment_amount');
            const paymentInfoDiv = document.getElementById('paymentInfo');

            if (paymentAmountInput) {
                paymentAmountInput.value = ''; // Clear the payment amount input field
                paymentInfoDiv.innerHTML = ''; // Clear the payment info section as well
            }
        }

        let paymentAmountInput;
        let totalPaymentsRequired = 0;
        let remainingBal = 0;
        let totalAmount = 0;
        let minimumPayment = 0;
        let paymentsMade = 0;

        function viewItems(orderId) {
            fetch(`/view-items/${orderId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modal = document.getElementById('my_modal_3');
                        if (modal) {
                            modal.showModal();
                            populateItemList(data.items);
                        }
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function populateItemList(items) {
    var itemList = document.getElementById('itemList');
    if (itemList) {
        itemList.innerHTML = '';  // Clear existing items
        items.forEach(item => {
            var listItem = document.createElement('li');
            listItem.classList.add('border', 'border-gray-200', 'rounded-lg', 'p-6', 'mb-6', 'flex', 'flex-col', 'lg:flex-row', 'bg-white', 'shadow-lg', 'hover:shadow-xl', 'transition-shadow', 'duration-300', 'ease-in-out');

            // Image container
            var imageContainer = document.createElement('div');
            imageContainer.classList.add('w-full', 'flex', 'justify-center', 'mb-4', 'lg:w-1/3', 'lg:mb-0');

            // Image element with fixed size
            var image = document.createElement('img');
            image.src = "{{ asset('storage/') }}/" + item.image_paths[0];
            image.alt = item.product_name;
            image.classList.add('h-48', 'w-48', 'object-contain', 'rounded-md', 'shadow-sm');  // Fixed size for image
            imageContainer.appendChild(image);

            // Details container
            var detailsContainer = document.createElement('div');
            detailsContainer.classList.add('flex-1', 'text-center', 'lg:text-left', 'lg:pl-6');

            // Product info with improved typography and spacing
            detailsContainer.innerHTML = `
                <p class="text-2xl font-bold mb-2 text-gray-900">${item.product_name}</p>
                <div class="grid grid-cols-2 gap-4 lg:grid-cols-1 lg:gap-3 text-gray-700">
                    <div><span class="font-semibold">Brand:</span> ${item.product_brand}</div>
                    <div><span class="font-semibold">Size:</span> ${item.size}</div>
                    <div><span class="font-semibold">Color:</span> ${item.color}</div>
                    <div><span class="font-semibold">Quantity:</span> ${item.quantity}</div>
                </div>
            `;

            // Append image and details to the list item
            listItem.appendChild(imageContainer);
            listItem.appendChild(detailsContainer);

            // Add list item to the item list
            itemList.appendChild(listItem);
        });
    }
}

        function viewLayawayPayments(orderId) {
            fetch(`/layaway-payments/${orderId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modal = document.getElementById('layaway_modal');
                        document.getElementById('payment_amount').min = Math.ceil(data.nextPaymentAmount *
                            100); // Convert to cents
                        // No decimals, round up to the nearest integer
                        // Round to 2 decimal places
                        paymentsMade = data.paymentsMade; // Store paymentsMade globally

                        if (modal) {
                            modal.showModal();
                            document.getElementById('order_id').value = orderId;
                            populateLayawayPayments(
                                data.allPayments, // Pass allPayments here
                                data.paymentsMade,
                                data.totalPaymentsRequired,
                                data.nextPaymentDate,
                                data.months,
                                data.nextPaymentAmount,
                                Number(data.totalAmount),
                                Number(data.remainingBalance),
                                Number(data.amount_paid),
                                data.declinedPayments
                            );
                        }
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function populateLayawayPayments(allPayments, paymentsMade, totalPaymentsRequiredInput,
            nextPaymentDate, months, nextPaymentAmount, totalAmountInput, remainingBalance, amountPaid, declinedPayments) {

            var paymentList = document.getElementById('layawayPaymentList');
            var totalAmountElement = document.getElementById('totalAmount');
            var paymentProgress = document.getElementById('paymentProgress');
            var nextPaymentDue = document.getElementById('nextPaymentDue');
            var nextPaymentAmountElement = document.getElementById('nextPaymentAmount');
            var amountPaidElement = document.getElementById('amount_paid');
            var remainingBalanceElement = document.getElementById('remainingBalance');

            if (remainingBalanceElement) {
                remainingBalanceElement.textContent = `Remaining Balance: ₱${remainingBalance.toFixed(2)}`;
            }

            remainingBal = remainingBalance;

            if (totalAmountElement) {
                totalAmountElement.textContent = `Total Order Amount: ₱${totalAmountInput.toFixed(2)}`;
            }

            if (paymentProgress && months) {
                paymentProgress.textContent =
                    `Payment Progress: ${paymentsMade}/${totalPaymentsRequiredInput} (${months} months)`;
            }

            if (amountPaidElement) {
                amountPaidElement.textContent = `Amount Paid: ₱${amountPaid.toFixed(2)}`;
            }

            if (nextPaymentDue) {
                nextPaymentDue.textContent =
                    `Next Payment Due: ${getNextPaymentDueDate(paymentsMade).format('MMMM D, YYYY')}`;
            }

            if (nextPaymentAmountElement) {
                nextPaymentAmountElement.textContent =
                    `Next Payment Amount: ₱${(Math.ceil(nextPaymentAmount * 100) / 100).toFixed(2)}`; // Convert to cents and back to decimal
            }

            if (paymentList) {
                paymentList.innerHTML = '';

                // Iterate over allPayments and display each payment
                allPayments.forEach(payment => {
                    var statusColorClass = payment.status === 'Pending' ? 'text-amber-600' :
                        (payment.status === 'Declined' ? 'text-red-600' : 'text-emerald-600');

                    var paymentLabel = payment.is_initial_payment ? 'Down Payment' : 'Payment';

                    var listItem = document.createElement('li');
                    listItem.classList.add('border', 'border-gray-200', 'rounded-md', 'p-4', 'mb-4', 'flex',
                        'flex-col', 'bg-white', 'shadow-sm');
                    listItem.innerHTML = `
            <p class="text-lg font-semibold mb-2 text-gray-800">${paymentLabel} Date: ${dayjs(payment.payment_date).format('MMMM D, YYYY')}</p>
            <p class="text-md text-gray-600">Amount: ₱${Number(payment.amount).toFixed(2)}</p>
            <p class="text-md text-gray-600">Status: <span class="${statusColorClass}">${payment.status}</span></p>
        `;

                    // Add a message below if the payment status is pending
                    if (payment.status === 'Pending') {
                        var message = document.createElement('p');
                        message.classList.add('text-sm', 'text-red-600', 'mt-2');
                        message.textContent =
                            "Please note: The next payment amount may not be finalized until this payment is accepted.";
                        listItem.appendChild(message);
                    }

                    paymentList.appendChild(listItem);
                });

                // Display Declined Payments
                declinedPayments.forEach(payment => {
                    var listItem = document.createElement('li');
                    listItem.classList.add('border', 'border-red-500', 'rounded-md', 'p-4', 'mb-4', 'flex',
                        'flex-col', 'bg-red-50', 'shadow-sm');
                    listItem.innerHTML = `
            <p class="text-lg font-semibold mb-2 text-red-800">Declined Payment</p>
            <p class="text-md text-gray-600">Amount: ₱${Number(payment.amount).toFixed(2)}</p>
            <p class="text-md text-gray-600">Date: ${dayjs(payment.payment_date).format('MMMM D, YYYY')}</p>
            <p class="text-md text-gray-600">Reason: ${payment.decline_reason || 'N/A'}</p>
        `;
                    paymentList.appendChild(listItem);
                });

                minimumPayment = nextPaymentAmount;
                paymentAmountInput.min = minimumPayment.toFixed(2);
                totalAmount = totalAmountInput;
                totalPaymentsRequired = totalPaymentsRequiredInput;
            }

        }


        function getNextPaymentDueDate(paymentsMade) {
            const today = dayjs();
            let nextDueDate = today;

            // Calculate the next due date based on the payment progress
            for (let i = 0; i <= paymentsMade; i++) {
                if (nextDueDate.date() <= 15) {
                    nextDueDate = nextDueDate.endOf('month'); // Next payment at the end of the current month
                } else {
                    nextDueDate = nextDueDate.add(1, 'month').date(15); // 15th of the next month
                }
            }

            return nextDueDate;
        }

        document.addEventListener('DOMContentLoaded', function() {
            paymentAmountInput = document.getElementById('payment_amount');
            const alertDiv = document.getElementById('alert');
            if (alertDiv) {
                setTimeout(() => {
                    alertDiv.classList.remove('opacity-0');
                    alertDiv.classList.add('opacity-100');
                }, 100);
                setTimeout(() => {
                    alertDiv.classList.remove('opacity-100');
                    alertDiv.classList.add('opacity-0');
                }, 5000);
            }

            const submitPaymentButton = document.getElementById('submitPaymentButton');
            const paymentInfoDiv = document.getElementById('paymentInfo');

            submitPaymentButton.addEventListener('click', function() {
                const formData = new FormData();
                const receiptFile = document.getElementById('payment_receipt').files[0];
                const orderId = document.getElementById('order_id').value;
                const paymentAmount = parseFloat(paymentAmountInput.value);

                const paymentAmountInCents = Math.round(paymentAmount * 100);
                if (receiptFile && orderId && paymentAmount >= minimumPayment) {
                    formData.append('payment_receipt', receiptFile);
                    formData.append('order_id', orderId);
                    formData.append('payment_amount', paymentAmount);
                    formData.append('_token', '{{ csrf_token() }}');

                    fetch('/add-layaway-payment', {
                            method: 'POST',
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                clearPaymentAmount(); // Clear payment amount input after submission
                                viewLayawayPayments(data.order_id); // Refresh the layaway payments list
                                showSuccess('Payment submitted successfully!');
                            } else {
                                showError('Failed to submit payment.');
                            }
                        })
                        .catch(error => {
                            showError('An error occurred while submitting payment.');
                        });
                } else {
                    showError(
                        'Please fill out all required fields and ensure the payment amount meets the minimum requirement.'
                    );
                }
            });

            function showSuccess(message) {
                const paymentSuccess = document.createElement('div');
                paymentSuccess.textContent = message;
                paymentSuccess.classList.add('text-green-600', 'font-semibold', 'my-2');
                document.body.appendChild(paymentSuccess);
                setTimeout(() => {
                    paymentSuccess.remove();
                }, 5000); // Hide the success message after 5 seconds
            }

            paymentAmountInput.addEventListener('input', function() {
                const paymentAmount = parseFloat(paymentAmountInput.value);
                if (!isNaN(paymentAmount)) {
                    const newRemainingBalance = remainingBal - paymentAmount;

                    if (newRemainingBalance <= 0) {
                        // Full payment scenario
                        paymentInfoDiv.innerHTML = `
                <p>Remaining Balance: ₱${remainingBal.toFixed(2)}</p>
                <p>Payment Amount: ₱${paymentAmount.toFixed(2)}</p>
                <p>Updated Balance: ₱${newRemainingBalance.toFixed(2)}</p>
                <p>Next Payment Amount: ₱0.00</p>
                <p>No further payments are needed as the full balance is paid.</p>
            `;
                    } else {
                        const newRemainingPayments = totalPaymentsRequired - paymentsMade -
                            1; // Deduct 1 for the current payment
                        const nextPaymentAmount = newRemainingBalance / newRemainingPayments;

                        let paymentInfoHtml = `
                <p>Remaining Balance: ₱${remainingBal.toFixed(2)}</p>
                <p>Payment Amount: ₱${paymentAmount.toFixed(2)}</p>
                <p>Updated Balance: ₱${newRemainingBalance.toFixed(2)}</p>
                <p>This payment is for: ${getNextPaymentDueDate(paymentsMade).format('MMMM D, YYYY')}</p>
                <p>Next Payment Amount: ₱${nextPaymentAmount.toFixed(2)}</p>
                <p>Next Payment Dates:</p>
            `;

                        // Calculate and display the next payment dates using dayjs
                        let nextDueDate = getNextPaymentDueDate(paymentsMade + 1);

                        // Calculate the next due dates based on the payment progress
                        for (let i = paymentsMade + 1; i < totalPaymentsRequired; i++) {
                            paymentInfoHtml += `<p>${nextDueDate.format('MMMM D, YYYY')}</p>`;

                            if (nextDueDate.date() <= 15) {
                                nextDueDate = nextDueDate.endOf(
                                    'month'); // Next payment at the end of the current month
                            } else {
                                nextDueDate = nextDueDate.add(1, 'month').date(
                                    15); // 15th of the next month
                            }
                        }

                        paymentInfoDiv.innerHTML = paymentInfoHtml;
                    }
                } else {
                    paymentInfoDiv.innerHTML = '';
                }
            });

            function getNextPaymentDueDate(paymentsMade) {
                const today = dayjs();
                let nextDueDate = today;

                // Calculate the next due date based on the payment progress
                for (let i = 0; i <= paymentsMade; i++) {
                    if (nextDueDate.date() <= 15) {
                        nextDueDate = nextDueDate.endOf('month'); // Next payment at the end of the current month
                    } else {
                        nextDueDate = nextDueDate.add(1, 'month').date(15); // 15th of the next month
                    }
                }

                return nextDueDate;
            }


            function showError(message) {
                const paymentError = document.getElementById('paymentError');
                paymentError.textContent = message;
                paymentError.classList.remove('hidden');
                setTimeout(() => {
                    paymentError.classList.add('hidden');
                }, 5000); // Hide the error after 5 seconds
            }
        });

        function showImageModal(element) {
            var modal = document.getElementById('imageModal');
            var modalImg = document.getElementById('modalImage');
            if (modal && modalImg) {
                modalImg.src = element.src;
                modal.showModal();
            }
        }

        // Show refund modal when the user clicks the refund button
function requestRefund(orderId) {
    var modal = document.getElementById('refundModal');
    var refundOrderId = document.getElementById('refundOrderId');

    refundOrderId.value = orderId;  // Set the order ID in the hidden input
    modal.showModal();  // Show the modal
}

// Close the modal when the close button is clicked
document.getElementById('closeRefundModal').addEventListener('click', function() {
    var modal = document.getElementById('refundModal');
    modal.close();
});

// Handle refund form submission
document.getElementById('refundForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var formData = new FormData(this);
    
    fetch('/request-refund', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Refund request submitted successfully!');
            location.reload();  // Optionally reload the page to reflect the change
        } else {
            alert('There was an error submitting your refund request.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An unexpected error occurred.');
    });
});

function openRefundImage(imageUrl) {
    if (imageUrl) {
        // Construct the full URL for the refund receipt image
        var fullImageUrl = "{{ asset('storage/') }}/" + imageUrl;

        // Open the image in a modal
        var modal = document.getElementById('refundImageModal');
        var modalImage = document.getElementById('refundImage');
        modalImage.src = fullImageUrl;
        modal.showModal();
    } else {
        alert('No refund image available.');
    }
}
    </script>



</x-app-layout>
