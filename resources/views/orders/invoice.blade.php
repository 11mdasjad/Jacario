<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Tax Invoice — {{ $order->order_number }} | JACARIO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #18181B;
            background: #FFFFFF;
            margin: 0;
            padding: 40px 20px;
            font-size: 13px;
            line-height: 1.5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #E4E4E7;
            padding: 40px;
            border-radius: 12px;
        }
        .brand {
            font-family: 'Cinzel', serif;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 4px;
            color: #0B0D10;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0B0D10;
            padding-bottom: 24px;
            margin-bottom: 30px;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0B0D10;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #F4F4F5;
            text-align: left;
            padding: 12px 14px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #E4E4E7;
        }
        td {
            padding: 14px;
            border-bottom: 1px solid #F4F4F5;
        }
        .totals {
            margin-left: auto;
            width: 300px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
        }
        .totals-row.grand {
            border-top: 2px solid #0B0D10;
            font-size: 16px;
            font-weight: bold;
            color: #0B0D10;
            padding-top: 10px;
            margin-top: 6px;
        }
        .footer-note {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E4E4E7;
            text-align: center;
            font-size: 11px;
            color: #71717A;
        }
        .print-btn {
            display: inline-block;
            background: #0B0D10;
            color: #FFFFFF;
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            cursor: pointer;
            border: none;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .container { border: none; padding: 0; }
        }
    </style>
</head>
<body>

<div class="container">
    
    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="print-btn">Print / Save as PDF</button>
    </div>

    <div class="header">
        <div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
                <img src="{{ asset('images/logo.png') }}" alt="JACARIO" style="height: 48px; width: auto; object-fit: contain;">
            </div>
            <div style="font-size: 11px; text-transform: uppercase; color: #A4845B; letter-spacing: 2px; margin-top: 2px;">The Polo, Perfected.</div>
            <div style="color: #71717A; font-size: 11px; margin-top: 8px;">
                JACARIO Flagship Atelier, 42 Heritage Blvd, Mumbai 400050<br>
                GSTIN: 27AAACJ8920C1Z8 | concierge@jacario.com
            </div>
        </div>
        <div class="invoice-title">
            <h2>TAX INVOICE</h2>
            <div style="font-family: monospace; font-size: 14px; font-weight: bold; color: #0B0D10; margin-top: 4px;">{{ $order->order_number }}</div>
            <div style="color: #71717A; font-size: 12px; margin-top: 4px;">Date: {{ $order->created_at->format('M j, Y') }}</div>
        </div>
    </div>

    <div class="grid-2">
        <div>
            <div style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #71717A; margin-bottom: 6px;">Billed & Shipped To:</div>
            <div style="font-weight: bold; font-size: 14px;">{{ $order->customer_name }}</div>
            <div style="color: #52525B; margin-top: 4px;">
                {{ $order->shipping_address['address_line_1'] ?? '' }}<br>
                @if(!empty($order->shipping_address['address_line_2']))
                    {{ $order->shipping_address['address_line_2'] }}<br>
                @endif
                {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['postal_code'] ?? '' }}<br>
                Phone: {{ $order->customer_phone }} | Email: {{ $order->customer_email }}
            </div>
        </div>
        <div>
            <div style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #71717A; margin-bottom: 6px;">Payment Information:</div>
            <div style="color: #52525B;">
                Payment Mode: <strong style="text-transform: uppercase;">{{ $order->payment_method }}</strong><br>
                Payment Status: <strong style="text-transform: uppercase;">{{ $order->payment_status }}</strong><br>
                @if($order->tracking_number)
                    Courier: <strong>{{ $order->courier_name }}</strong> (AWB: {{ $order->tracking_number }})
                @endif
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item Description</th>
                <th>SKU</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Unit Price</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong style="color: #0B0D10;">{{ $item->product_name }}</strong><br>
                        <span style="font-size: 11px; color: #71717A;">Size: {{ $item->size_name }} | Color: {{ $item->color_name }}</span>
                    </td>
                    <td style="font-family: monospace; font-size: 11px;">{{ $item->sku }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">₹{{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right; font-weight: 600;">₹{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="totals-row">
            <span>Subtotal</span>
            <span>₹{{ number_format($order->subtotal, 2) }}</span>
        </div>
        @if($order->discount_amount > 0)
            <div class="totals-row" style="color: #047857;">
                <span>Discount ({{ $order->coupon_code }})</span>
                <span>- ₹{{ number_format($order->discount_amount, 2) }}</span>
            </div>
        @endif
        <div class="totals-row">
            <span>Express Shipping</span>
            <span>{{ $order->shipping_amount == 0 ? 'Complimentary' : '₹' . number_format($order->shipping_amount, 2) }}</span>
        </div>
        <div class="totals-row grand">
            <span>Grand Total</span>
            <span>₹{{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>

    <div class="footer-note">
        Thank you for choosing JACARIO Haute Apparel. All Polo T-Shirts come with a 15-day complimentary exchange warranty.<br>
        For inquiries, please contact concierge@jacario.com or call +91 (0) 22 8900 1200.
    </div>

</div>

</body>
</html>
