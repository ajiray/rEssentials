<h1>New Order Placed</h1>
<p>Order ID: {{ $order->id }}</p>
<p>Customer: {{ $order->customer->name }}</p>
<p>Total Amount: ₱{{ number_format($order->total_amount, 2) }}</p>
