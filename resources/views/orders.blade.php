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
                                                Shipping Status
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
                                                        @elseif ($order->shipping_status == 'preparing') bg-amber-100 text-amber-600 @endif">
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
                                    @else
                                        {{ $order->payment_method }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="viewItems({{ $order->id }})"
                                    class="text-indigo-600 hover:text-indigo-800 font-semibold">View
                                    Items</button>
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
                    <span class="font-semibold text-velvet">Shipping Status:</span>
                    <span
                        class="px-2 py-1 rounded-full
                        @if ($order->shipping_status == 'delivered') bg-emerald-100 text-emerald-600
                        @elseif ($order->shipping_status == 'shipped') bg-blue-100 text-blue-600
                        @elseif ($order->shipping_status == 'preparing') bg-amber-100 text-amber-600 @endif">
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
                    <span class="">

                        @if ($order->payment_method == 'layaway')
                            <button onclick="viewLayawayPayments({{ $order->id }})"
                                class="text-indigo-600 hover:text-indigo-800 font-semibold">
                                {{ $order->payment_method }}
                            </button>
                        @else
                            {{ $order->payment_method }}
                        @endif

                    </span>
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
                itemList.innerHTML = '';
                items.forEach(item => {
                    var listItem = document.createElement('li');
                    listItem.classList.add('border', 'border-gray-200', 'rounded-md', 'p-4', 'mb-4', 'flex',
                        'flex-col', 'bg-white', 'shadow-sm', 'lg:flex-row');
                    var imageContainer = document.createElement('div');
                    imageContainer.classList.add('w-full', 'flex', 'justify-center', 'mb-4', 'lg:w-1/3', 'lg:mb-0');
                    var image = document.createElement('img');
                    image.src = "{{ asset('storage/') }}/" + item.image_paths[0];
                    image.alt = item.product_name;
                    image.classList.add('h-auto', 'w-full', 'max-w-[300px]', 'rounded-md');
                    imageContainer.appendChild(image);
                    var detailsContainer = document.createElement('div');
                    detailsContainer.classList.add('flex-1', 'text-center', 'lg:text-left', 'lg:pl-4');
                    detailsContainer.innerHTML = `
                <p class="text-xl font-semibold mb-2 text-gray-800">${item.product_name}</p>
                <div class="grid grid-cols-2 gap-4 lg:grid-cols-1 lg:gap-2 text-gray-600">
                    <div><strong class="block lg:inline-block">Brand:</strong> ${item.product_brand}</div>
                    <div><strong class="block lg:inline-block">Size:</strong> ${item.size}</div>
                    <div><strong class="block lg:inline-block">Color:</strong> ${item.color}</div>
                    <div><strong class="block lg:inline-block">Quantity:</strong> ${item.quantity}</div>
                </div>
            `;
                    listItem.appendChild(imageContainer);
                    listItem.appendChild(detailsContainer);
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
            nextPaymentDate, months, nextPaymentAmount, totalAmountInput, remainingBalance, amountPaid) {

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
                    var statusColorClass = payment.status === 'Pending' ? 'text-amber-600' : 'text-emerald-600';
                    var paymentLabel = payment.is_initial_payment ? 'Down Payment' : 'Payment';

                    var listItem = document.createElement('li');
                    listItem.classList.add('border', 'border-gray-200', 'rounded-md', 'p-4', 'mb-4', 'flex',
                        'flex-col', 'bg-white', 'shadow-sm');
                    listItem.innerHTML = `
                <p class="text-lg font-semibold mb-2 text-gray-800">${paymentLabel} Date: ${dayjs(payment.payment_date).format('MMMM D, YYYY')}</p>
                <p class="text-md text-gray-600">Amount: ₱${Number(payment.amount).toFixed(2)}</p>
                <p class="text-md text-gray-600">Status: <span class="${statusColorClass}">${payment.status}</span></p>
                ${payment.status === 'Pending' ? '<p class="text-sm text-red-500">(The next payment amount is not accurate until this payment is confirmed)</p>' : ''}
            `;
                    paymentList.appendChild(listItem);
                });

                minimumPayment = nextPaymentAmount;
                paymentAmountInput.min = minimumPayment.toFixed(
                    2); // Ensure the minimum amount is rounded to 2 decimal places
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
    </script>



</x-app-layout>
