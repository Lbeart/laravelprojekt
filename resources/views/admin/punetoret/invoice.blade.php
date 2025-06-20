<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura Ditore - {{ $punetor->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background: #f8f9fa;
            font-size: 14px;
        }

        .invoice-box {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 20px 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .logo {
            width: 150px;
            height: auto;
        }

        .top-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .top-info .right {
            text-align: right;
        }

        .top-info strong {
            display: block;
        }

        .company-info {
            margin: 20px 0;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            min-width: 600px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            font-size: 13px;
        }

        .buttons {
            margin-top: 30px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .buttons button,
        .buttons a {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .buttons button {
            background-color: #007bff;
        }

        .buttons a {
            background-color: #28a745;
        }

        @media (max-width: 768px) {
            .top-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .top-info .right {
                text-align: left;
                width: 100%;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 6px;
            }

            .buttons {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="top-info">
            <div class="left">
                <img src="{{ asset('images/llogo.png') }}" alt="Logo" class="logo">
            </div>
            <div class="right">
                <strong>FATURË DITORE SHITJESH</strong>
                Data: {{ now()->format('d.m.Y') }}<br>
                Punëtori: {{ $punetor->name }}<br>
                Email: {{ $punetor->email }}
            </div>
        </div>

        <div class="company-info">
            <strong>Informata Kompanie:</strong><br>
            📞 Tel: 044996926<br>
            📧 Email: salloniiperdevetepihavebrillant2006<br>
            📍 Adresa: Rruga Gjergj Fishta, Lipjan
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Emri Klientit</th>
                        <th>Produkti</th>
                        <th>Sasia</th>
                        <th>Qmimi</th>
                        <th>Total (€)</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        use App\Models\Klienti;
                        $shitjetDitore = Klienti::where('user_id', $punetor->id)
                            ->whereDate('created_at', today())
                            ->get();
                        $totali = 0;
                    @endphp
                    @foreach($shitjetDitore as $index => $s)
                        @php 
                            $total = $s->sasia * $s->qmimi; 
                            $totali += $total; 
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $s->emri }} {{ $s->mbiemri }}</td>
                            <td>{{ $s->produkti }}</td>
                            <td>{{ $s->sasia }}</td>
                            <td>{{ number_format($s->qmimi, 2) }}</td>
                            <td>{{ number_format($total, 2) }} €</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="text-right"><strong>Totali i Shitjeve</strong></td>
                        <td><strong>{{ number_format($totali, 2) }} €</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>📝 Nënshkrimi i Punëtorit: ____________________________</p>
            <p>📝 Nënshkrimi i Kompanisë: ____________________________</p>
        </div>

        <div class="buttons">
            <button onclick="window.print()">🖨️ Printo</button>
            <a href="{{ route('admin.dashboard') }}">↩️ Kthehu te Paneli</a>
        </div>
    </div>
</body>
</html>
