<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - Infinity Wedding</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500&family=Open+Sans&display=swap');

        body {
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            font-family: 'Open Sans', sans-serif;
        }
        .invoice-container {
            width: 720px;
            margin: 40px auto;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
            display: flex;
            border-radius: 10px;
            overflow: hidden;
        }
        .left-panel {
            background: #415B4A;
            color: #fff;
            width: 35%;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .left-panel h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .left-panel p {
            margin: 5px 0;
            font-size: 14px;
        }
        .signature {
            margin-top: 40px;
            font-style: italic;
            font-size: 13px;
        }
        .right-panel {
            width: 65%;
            padding: 30px;
            background: #fff;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-header h1 {
            font-size: 22px;
            font-family: 'Playfair Display', serif;
            color: #333;
        }
        .invoice-details {
            margin-top: 20px;
            font-size: 14px;
        }
        .invoice-details p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            font-size: 14px;
        }
        table th, table td {
            text-align: left;
            padding: 10px 5px;
            border-bottom: 1px solid #eee;
        }
        table th {
            color: #415B4A;
            font-weight: 600;
        }
        .totals {
            margin-top: 20px;
            font-size: 14px;
        }
        .totals div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .grand-total {
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }
        .flower-deco {
            position: absolute;
            right: 40px;
            top: 40px;
            width: 60px;
            opacity: 0.2;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <div class="left-panel">
        <div>
            <h2>Infinity Wedding</h2>
            <p><strong>Alamat:</strong><br>Jl. Cinta Abadi No. 88<br>Yogyakarta</p>
            <p><strong>Phone:</strong><br>+62 812-3456-7890</p>
            <p><strong>Email:</strong><br>infinity@wedding.com</p>
            <p><strong>Payment Method:</strong><br>Bank Transfer - BCA<br>a/n Infinity Wedding Organizer</p>
        </div>
        <div class="signature">
            Hormat Kami,<br><br>
            <strong>Infinity Team</strong>
        </div>
    </div>
    <div class="right-panel">
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <div>
                <p><strong>No:</strong> {{ $invoice->nomor_invoice }}</p>
                <p><strong>Tanggal:</strong> {{ $invoice->tanggal_invoice }}</p>
            </div>
        </div>

        <div class="invoice-details">
            <p><strong>Untuk:</strong> {{ $invoice->klien->pengguna->nama ?? '-' }}</p>
            <p>{{ $invoice->klien->pengguna->alamat ?? '-' }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th style="text-align:right;">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach(explode("\n", $invoice->deskripsi) as $item)
                    <tr>
                        <td>{{ $item }}</td>
                        <td style="text-align:right;">-</td>
                    </tr>
                @endforeach
                <tr>
                    <td><strong>Subtotal</strong></td>
                    <td style="text-align:right;">Rp {{ number_format($invoice->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Diskon (0%)</td>
                    <td style="text-align:right;">Rp 0</td>
                </tr>
            </tbody>
        </table>

        <div class="totals">
            <div class="grand-total">
                <span>GRAND TOTAL</span>
                <span>Rp {{ number_format($invoice->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
