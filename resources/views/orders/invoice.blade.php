<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 10mm;
            size: A4;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #000;
            font-size: 11px;
            line-height: 1.3;
        }

        .page-content {
            padding: 10px;
            max-height: 275mm;
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 4px 0;
        }

        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 8px 0;
        }

        .order-info {
            text-align: right;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .info-column {
            flex: 1;
            min-width: 0;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            margin: 0 0 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0 10px 0;
            font-size: 10px;
            page-break-inside: avoid;
        }

        th {
            background-color: #f5f5f5;
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
        }

        td {
            border: 1px solid #000;
            padding: 5px 4px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            width: 250px;
            margin-left: auto;
            margin-top: 10px;
            font-size: 11px;
            page-break-inside: avoid;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }

        .summary-total {
            border-top: 2px solid #000;
            font-weight: bold;
            font-size: 12px;
            padding-top: 5px;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 9px;
            color: #666;
            page-break-inside: avoid;
        }

        .compact-text {
            margin: 3px 0;
            font-size: 10px;
            word-wrap: break-word;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-placeholder {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .address-block {
            background-color: #f9f9f9;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #eee;
        }

        /* Compact table styles */
        .compact-table th,
        .compact-table td {
            padding: 4px 3px;
        }

        .compact-table th {
            font-size: 10px;
        }

        .compact-table td {
            font-size: 10px;
        }

        /* Ensure everything stays on one page */
        .single-page {
            position: relative;
            min-height: 275mm;
        }

        /* Limit items to fit on one page */
        .limited-items {
            max-height: 150px;
            overflow: hidden;
        }

        @media print {
            body {
                margin: 0;
                font-size: 10px;
            }

            .page-content {
                padding: 10mm;
                max-height: 270mm;
            }

            table {
                font-size: 9px;
            }

            .compact-text {
                font-size: 9px;
            }
        }
    </style>
</head>

<body>
    <div class="single-page">
        <div class="page-content">
            <!-- Header -->
            <div class="header">
                <div class="logo-container">
                    @if (file_exists(public_path('assets/images/logo1.png')))
                        <img src="{{ public_path('assets/images/logo1.png') }}" alt="Nova Studio Logo" width="60"
                            height="40">
                    @else
                        <div class="logo-placeholder">Nova Studio</div>
                    @endif
                    <div>
                        <h1 class="company-name">Nova Studio</h1>
                        <p class="compact-text">Fashion Redefined</p>
                        <p class="compact-text"><strong>Email:</strong> support@novastudio.com</p>
                        <p class="compact-text"><strong>Phone:</strong> +855 123456789</p>
                    </div>
                </div>
                <div class="order-info">
                    <h2 class="invoice-title">INVOICE</h2>
                    <p class="compact-text"><strong>Order Number:</strong> {{ $order->order_number }}</p>
                    <p class="compact-text"><strong>Invoice Date:</strong>
                        {{ $order->created_at->setTimezone('Asia/Phnom_Penh')->format('d/m/Y H:i') }}</p>
                    <p class="compact-text"><strong>Order Status:</strong> {{ ucfirst($order->order_status) }}</p>
                    <p class="compact-text"><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                    <p class="compact-text"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                    @if ($order->payment_date)
                        <p class="compact-text"><strong>Payment Date:</strong>
                            {{ $order->payment_date->setTimezone('Asia/Phnom_Penh')->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>

            <!-- Billing and Shipping Information -->
            <div class="info-row">
                <!-- Billing Information -->
                <div class="info-column">
                    <h3 class="section-title">BILLING INFORMATION</h3>
                    <div class="address-block">
                        @if ($order->billingAddress)
                            <p class="compact-text"><strong>Name:</strong> {{ $order->billingAddress->full_name }}</p>
                            <p class="compact-text"><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                            <p class="compact-text"><strong>Phone:</strong> {{ $order->billingAddress->phone }}</p>
                            <p class="compact-text"><strong>Address:</strong>
                                {{ $order->billingAddress->address_line1 }}</p>
                            @if ($order->billingAddress->address_line2)
                                <p class="compact-text">{{ $order->billingAddress->address_line2 }}</p>
                            @endif
                            <p class="compact-text">{{ $order->billingAddress->city }},
                                {{ $order->billingAddress->state }} {{ $order->billingAddress->zip_code }}</p>
                            <p class="compact-text">{{ $order->billingAddress->country }}</p>
                        @else
                            <p class="compact-text"><strong>Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                            <p class="compact-text"><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                            <p class="compact-text"><em>Billing address not specified</em></p>
                        @endif
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="info-column">
                    <h3 class="section-title">SHIPPING INFORMATION</h3>
                    <div class="address-block">
                        @if ($order->shippingAddress)
                            <p class="compact-text"><strong>Name:</strong> {{ $order->shippingAddress->full_name }}</p>
                            <p class="compact-text"><strong>Phone:</strong> {{ $order->shippingAddress->phone }}</p>
                            <p class="compact-text"><strong>Address:</strong>
                                {{ $order->shippingAddress->address_line1 }}</p>
                            @if ($order->shippingAddress->address_line2)
                                <p class="compact-text">{{ $order->shippingAddress->address_line2 }}</p>
                            @endif
                            <p class="compact-text">{{ $order->shippingAddress->city }},
                                {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip_code }}</p>
                            <p class="compact-text">{{ $order->shippingAddress->country }}</p>
                        @else
                            <p class="compact-text"><em>Shipping address not specified</em></p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <h3 class="section-title">ORDER DETAILS</h3>
            <table class="compact-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 35%;">Product</th>
                        <th style="width: 10%;">Size</th>
                        <th style="width: 10%;">Color</th>
                        <th style="width: 8%;" class="text-center">Qty</th>
                        <th style="width: 16%;" class="text-right">Unit Price (USD)</th>
                        <th style="width: 16%;" class="text-right">Total (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $itemCount = 0;
                        $maxItems = 8; // Limit to fit on one page
                        $totalItems = count($order->items);
                    @endphp
                    @foreach ($order->items as $index => $item)
                        @if ($itemCount < $maxItems)
                            @php
                                $variantDetails = json_decode($item->variant_details, true) ?? [];
                                $size = $variantDetails['size'] ?? 'N/A';
                                $color = $variantDetails['color'] ?? 'N/A';
                                $itemCount++;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ Str::limit($item->product_name, 50) }}</td>
                                <td>{{ $size }}</td>
                                <td>{{ $color }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-right">${{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach

                    <!-- Show "more items" message if truncated -->
                    @if ($totalItems > $maxItems)
                        <tr>
                            <td colspan="7" class="text-center" style="font-style: italic;">
                                ... and {{ $totalItems - $maxItems }} more item(s)
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <!-- Order Summary -->
            <div class="summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>${{ number_format($order->shipping_amount, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Tax (8%):</span>
                    <span>${{ number_format($order->tax_amount, 2) }}</span>
                </div>
                @if ($order->discount_amount > 0)
                    <div class="summary-row">
                        <span>Discount:</span>
                        <span>-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="summary-row summary-total">
                    <span>TOTAL:</span>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Order Notes -->
            @if ($order->customer_notes)
                <div style="margin-top: 10px;">
                    <h3 class="section-title">CUSTOMER NOTES</h3>
                    <p class="compact-text">{{ Str::limit($order->customer_notes, 100) }}</p>
                </div>
            @endif

            <!-- Footer -->
            <div class="footer">
                <p><strong>Thank you for shopping with Nova Studio!</strong></p>
                <p>This invoice was generated on {{ now()->setTimezone('Asia/Phnom_Penh')->format('d/m/Y H:i') }}</p>
                <p>For any inquiries, please contact us at support@novastudio.com or call +855 123456789</p>
                <p>Terms & Conditions: All sales are final. Returns accepted within 30 days with original packaging.</p>
            </div>
        </div>
    </div>
</body>

</html>
