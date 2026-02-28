<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Return Order - {{ $goodsReturn->return_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #4F46E5;
        }

        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-table td {
            vertical-align: top;
            padding: 5px;
        }

        .label {
            font-weight: bold;
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background: #F3F4F6;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #E5E7EB;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #F3F4F6;
        }

        .reason-box {
            background: #FEF3C7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>REJECTION / RETURN ADVICE</h1>
        <p>{{ $goodsReturn->return_number }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <div class="label">From</div>
                <strong>{{ config('app.name') }}</strong><br>
                Warehouse: {{ $goodsReturn->goodsReceipt->warehouse->name }}
            </td>
            <td width="50%" style="text-align: right;">
                <div class="label">Return Date</div>
                {{ $goodsReturn->created_at->format('d M Y') }}<br>
                <div class="label" style="margin-top: 10px;">Original Receipt</div>
                {{ $goodsReturn->goodsReceipt->receipt_number }} ({{ $goodsReturn->goodsReceipt->received_date }})
            </td>
        </tr>
    </table>

    <div class="reason-box">
        <div class="label" style="color: #92400E;">Reason for Rejection</div>
        <p style="margin: 5px 0 0 0;">{{ $goodsReturn->reason }}</p>
        <div class="label" style="color: #92400E; margin-top: 10px;">Requested Resolution</div>
        <p style="margin: 5px 0 0 0; font-weight: bold;">{{ $goodsReturn->resolution }}</p>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Product SKU</th>
                <th>Product Name</th>
                <th style="text-align: right;">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($goodsReturn->items as $item)
                <tr>
                    <td><code>{{ $item->product->sku }}</code></td>
                    <td>{{ $item->product->name }}</td>
                    <td style="text-align: right;">{{ $item->quantity }} {{ $item->product->unit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <table width="100%">
            <tr>
                <td width="50%">
                    <div style="border-bottom: 1px solid #ccc; width: 150px; height: 80px;"></div>
                    <div class="label" style="margin-top: 5px;">Warehouse Manager</div>
                </td>
                <td width="50%" style="text-align: right;">
                    <div style="border-bottom: 1px solid #ccc; width: 150px; height: 80px; display: inline-block;">
                    </div>
                    <div class="label" style="margin-top: 5px;">Supplier Acknowledgment</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Generated on {{ now()->format('Y-m-d H:i:s') }} | This is a system-generated document.
    </div>
</body>

</html>