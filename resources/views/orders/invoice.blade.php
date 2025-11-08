<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #000;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 10px 0;
        }

        .order-info {
            text-align: right;
        }

        .billing-section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        td {
            border: 1px solid #000;
            padding: 8px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            width: 300px;
            margin-left: auto;
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }

        .summary-total {
            border-top: 2px solid #000;
            font-weight: bold;
            font-size: 14px;
            padding-top: 8px;
            margin-top: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #000;
            font-size: 11px;
        }

        @media print {
            body {
                margin: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div style="display: flex; align-items: center; gap: 25px;">
            <img src="{{ public_path('assets/images/logo.png') }}" alt="Outfit 818 Logo" width="80" height="80">
            <div>
                <h1 class="company-name">OUTFIT 818</h1>
                {{-- <p>Fashion Redefined</p> --}}
            </div>
        </div>
        <div class="order-info">
            <h2 class="invoice-title">INVOICE</h2>
            <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->setTimezone('Asia/Phnom_Penh')->format('d M Y, h:i A') }}
            </p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>
    </div>

    <!-- Billing Information -->
    <div class="billing-section">
        <h3 class="section-title">BILLING INFORMATION</h3>
        <p><strong>Name:</strong> {{ $order->name }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
        <p><strong>Address:</strong> {{ $order->address }}, {{ $order->city }}, {{ $order->state }}
            {{ $order->zip }}</p>
    </div>

    <!-- Order Items -->
    <h3 class="section-title">ORDER DETAILS</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Size</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Price (USD)</th>
                <th class="text-right">Total (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->size }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                    <td class="text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Order Summary -->
    <div class="summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>${{ number_format($order->total_amount, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>Tax (0%):</span>
            <span>$0.00</span>
        </div>
        <div class="summary-row">
            <span>Shipping:</span>
            <span>Free</span>
        </div>
        <div class="summary-row summary-total">
            <span>TOTAL:</span>
            <span>${{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Thank you for shopping with Outfit 818!</strong></p>
        <p>This invoice was generated on {{ now()->setTimezone('Asia/Phnom_Penh')->format('d M Y, h:i A') }}</p>
    </div>
</body>

</html>
