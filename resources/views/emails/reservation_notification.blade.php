<!DOCTYPE html>
<html>
<head>
    <title>Reservation Notification</title>
</head>
<body>
    <h2>New Reservation Order</h2>
    <p>A new reservation has been placed:</p>
    <ul>
        <li><strong>Product:</strong> {{ $reservation->product->name }}</li>
        <li><strong>Variant:</strong> {{ $reservation->variant->color }} - {{ $reservation->variant->size }}</li>
        <li><strong>Quantity:</strong> {{ $reservation->quantity }}</li>
        <li><strong>Total Price:</strong> ₱{{ number_format($reservation->total_price, 2) }}</li>
        <li><strong>Down Payment:</strong> ₱{{ number_format($reservation->down_payment, 2) }}</li>
        <li><strong>Status:</strong> {{ $reservation->status }}</li>
        <li><strong>Reservation Date:</strong> {{ $reservation->reservation_date->format('F j, Y') }}</li>
    </ul>
</body>
</html>