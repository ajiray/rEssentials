<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Request</title>
</head>
<body>
    <h1>Refund Request for Order #{{ $order->id }}</h1>
    <p><strong>Customer Name:</strong> {{ $order->customer->name }}</p>
    <p><strong>Refund Reason:</strong> {{ $order->refund_reason }}</p>
    <p><strong>Refund Payment Method:</strong> {{ ucfirst($order->refund_payment_method) }}</p>
    <p><strong>Payment Details:</strong> {{ $order->refund_payment_details }}</p>
    <p>The customer has requested a refund for this order. Please review and process the request.</p>
</body>
</html>