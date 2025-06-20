<!DOCTYPE html>  
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Faturat për Muajin {{ DateTime::createFromFormat('!m', $muaji)->format('F') }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
            font-size: 14px;
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            width: 100px;
            max-width: 100%;
            height: auto;
        }

        .contact-info {
            font-size: 13px;
            margin-top: 8px;
            color: #333;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        input[type="text"] {
            padding: 8px;
            width: 250px;
            font-size: 14px;
        }

        button[type="submit"] {
            padding: 8px 15px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .table-wrapper table {
            width: 100%;
            min-width: 700px; /* scroll në telefon nëse s’ka vend */
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .actions {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        @media (max-width: 768px) {
            input[type="text"] {
                width: 100%;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 6px;
                white-space: nowrap; /* mban tekstin në një rresht */
            }

            .table-wrapper {
                padding: 5px;
                box-shadow: none;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<form method="GET" action="{{ route('admin.kerko.klient') }}">
    <input type="text" name="emri" placeholder="Kërko klientin me emër..." required>
    <button type="submit">Kërko</button>
</form>

<div class="header">
    <img src="{{ asset('images/llogo.png') }}" alt="Logo">
    <h2>Faturat për Muajin {{ DateTime::createFromFormat('!m', $muaji)->format('F Y') }}</h2>
    <div class="contact-info">
        📍 Rruga Gjergj Fishta, Lipjan &nbsp; | &nbsp;
        ☎️ 044996926 &nbsp; | &nbsp;
        📧 salloniiperdevetepihavebrillant2006
    </div>
</div>

@if($klientet->isEmpty())
    <p style="text-align: center;">Nuk ka të dhëna për këtë muaj.</p>
@else
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Punëtori</th>
                    <th>Klienti</th>
                    <th>Produkti</th>
                    <th>Sasia</th>
                    <th>Çmimi</th>
                    <th>Totali</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @php $nr = 1; $gjithsej = 0; @endphp
                @foreach($klientet as $k)
                    <tr>
                        <td>{{ $nr++ }}</td>
                        <td>{{ $k->punetor->name ?? '—' }}</td>
                        <td>{{ $k->emri }} {{ $k->mbiemri }}</td>
                        <td>{{ $k->produkti }}</td>
                        <td>{{ $k->sasia }}</td>
                        <td>{{ number_format($k->qmimi, 2) }} €</td>
                        <td>{{ number_format($k->sasia * $k->qmimi, 2) }} €</td>
                        <td>{{ \Carbon\Carbon::parse($k->created_at)->format('d/m/Y') }}</td>
                    </tr>
                    @php $gjithsej += $k->sasia * $k->qmimi; @endphp
                @endforeach
                <tr>
                    <th colspan="6" style="text-align: right;">Totali:</th>
                    <th colspan="2">{{ number_format($gjithsej, 2) }} €</th>
                </tr>
            </tbody>
        </table>
    </div>
@endif

<div class="actions">
    <button class="btn" onclick="window.print()">🖨️ Printo</button>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">🔙 Kthehu te Dashboardi</a>
</div>

<div class="footer">
    Faturë gjeneruar automatikisht - {{ now()->format('d.m.Y H:i') }}
</div>

</body>
</html>
