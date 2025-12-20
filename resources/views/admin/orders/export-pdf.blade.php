<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders Export - {{ $fromDate }} to {{ $toDate }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
        }
        
        .header h1 {
            color: #1e40af;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #6b7280;
        }
        
        .summary {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
        }
        
        .summary-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 5px;
        }
        
        .table-container {
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background-color: #3b82f6;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-confirmed { background-color: #dbeafe; color: #1e40af; }
        .status-processing { background-color: #ede9fe; color: #5b21b6; }
        .status-shipped { background-color: #e0e7ff; color: #3730a3; }
        .status-delivered { background-color: #d1fae5; color: #065f46; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        
        .payment-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
        }
        
        .payment-pending { background-color: #fef3c7; color: #92400e; }
        .payment-paid { background-color: #d1fae5; color: #065f46; }
        .payment-failed { background-color: #fee2e2; color: #991b1b; }
        
        .amount {
            font-weight: bold;
            color: #059669;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #eff6ff !important;
        }
        
        .total-row td {
            border-top: 2px solid #3b82f6;
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Orders Export Report</h1>
        <p>Date Range: {{ $fromDate }} to {{ $toDate }}</p>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}</p>
    </div>
    
    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Orders</div>
                <div class="summary-value">{{ $orderCount }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Revenue</div>
                <div class="summary-value">${{ number_format($totalAmount, 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Date Range</div>
                <div class="summary-value">{{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</div>
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Items</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td style="font-family: 'Courier New', monospace; font-weight: bold;">
                        {{ $order->order_number }}
                    </td>
                    <td>
                        <div style="font-weight: bold;">{{ $order->user->name ?? 'Guest' }}</div>
                        <div style="color: #6b7280; font-size: 10px;">{{ $order->user->email ?? 'No email' }}</div>
                    </td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="amount">${{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        <span class="status-badge status-{{ $order->order_status }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="payment-badge payment-{{ $order->payment_status }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td>{{ $order->items->count() }}</td>
                </tr>
                @endforeach
                <!-- Total Row -->
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total:</td>
                    <td style="color: #059669; font-size: 12px;">${{ number_format($totalAmount, 2) }}</td>
                    <td colspan="3">{{ $orderCount }} orders</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="footer">
        <p>This report was generated automatically from the system.</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>