<!DOCTYPE html> 
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Fatura për Klientin</title>
    <style>
        @media print {
            body {
                margin: 0;
            }

            .actions {
                display: none;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 40px;
            background: #f8f9fa;
        }

        .invoice-box {
            max-width: 900px;
            width: 100%;
            background: #fff;
            margin: auto;
            padding: 30px 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .logo {
            width: 160px;
            max-width: 100%;
            margin-bottom: 10px;
        }

        .company-info {
            text-align: right;
            font-size: 14px;
            flex: 1 1 200px;
        }

        hr {
            margin: 20px 0;
        }

        table {
            width: 100%;
            line-height: inherit;
            border-collapse: collapse;
            margin-bottom: 20px;
            overflow-x: auto;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 15px;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .total {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
        }

        .barcode {
            margin-top: 40px;
            text-align: center;
        }

        .signatures {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
            min-width: 200px;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .actions {
            margin-top: 30px;
            text-align: center;
        }

        .actions button,
        .actions a {
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
        }

        .actions button {
            background-color: #007BFF;
        }

        .actions a {
            background-color: #28a745;
        }

        @media (max-width: 600px) {
            body {
                margin: 10px;
            }

            .invoice-box {
                padding: 20px 15px;
            }

            table th, table td {
                font-size: 13px;
                padding: 8px;
            }

            .company-info {
                text-align: left;
                margin-top: 10px;
            }

            .signatures {
                flex-direction: column;
                align-items: center;
            }

            .signature-box {
                width: 100%;
                margin-bottom: 20px;
            }

            .barcode img {
                width: 100%;
                max-width: 300px;
            }

            .total {
                font-size: 14px;
            }

            .actions button,
            .actions a {
                width: 100%;
                max-width: 300px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <img src="{{ asset('images/llogo.png') }}" class="logo" alt="Logo">
            <div class="company-info">
                <strong>FATURË</strong><br>
                Data: {{ now()->format('d.m.Y') }}<br>
                Klienti: {{ $klient->emri }} {{ $klient->mbiemri }}
            </div>
        </div>

        <hr>

        <p><strong>Informata të Kompanisë:</strong><br>
            📞 Tel: 044996926<br>
            📧 Email: salloniiperdevetepihavebrillant2006<br>
            📍 Adresa: Rruga Gjergj Fishta, Lipjan
        </p>

        <table>
            <thead>
                <tr>
                    <th>Produkti</th>
                    <th>Sasia</th>
                    <th>Qmimi (€)</th>
                    <th>Total (€)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $klient->produkti }}</td>
                    <td>{{ $klient->sasia }}</td>
                    <td>{{ number_format($klient->qmimi, 2) }}</td>
                    <td>{{ number_format($klient->sasia * $klient->qmimi, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <p class="total">Totali i përgjithshëm: {{ number_format($klient->sasia * $klient->qmimi, 2) }} €</p>

        <div class="barcode">
            <img src="data:image/png;base64,{{ $barcode }}" alt="Barkodi" width="300">
        </div>

        <div class="signatures">
            <div class="signature-box">
                <p>Faturoi</p>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <p>Pranoi</p>
                <div class="signature-line"></div>
            </div>
        </div>

        <div class="actions">
            <button onclick="window.print()">🖨️ Printo Faturën</button>
            <a href="{{ url('/user/dashboard') }}">↩️ Kthehu te Dashboardi</a>
        </div>
    </div>
</body>
</html>
