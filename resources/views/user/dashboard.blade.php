<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 5px;
            color: #111827;
            text-align: center;
        }

        h4 {
            margin-top: 40px;
            font-size: 20px;
            color: #1e40af;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 6px;
        }

        p {
            margin-bottom: 15px;
        }

        form {
            margin-bottom: 20px;
        }

        input, button {
            width: 100%;
            padding: 10px 12px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            font-size: 15px;
        }

        input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        button {
            background-color: #3b82f6;
            color: white;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            width: auto;
            padding: 10px 18px;
        }

        button:hover {
            background-color: #2563eb;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 30px;
        }

        table {
            width: 100%;
            min-width: 700px;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 16px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        th {
            background-color: #3b82f6;
            color: #fff;
            font-weight: 600;
        }

        tr:hover td {
            background-color: #f3f4f6;
        }

        td {
            color: #374151;
        }

        .btn-link {
            padding: 7px 12px;
            font-size: 14px;
            border-radius: 6px;
            margin-right: 5px;
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }

        .btn-invoice {
            background-color: #22c55e;
            color: white;
        }

        .btn-invoice:hover {
            background-color: #16a34a;
        }

        .btn-edit {
            background-color: #facc15;
            color: #1f2937;
        }

        .btn-edit:hover {
            background-color: #eab308;
        }

        .btn-delete {
            background-color: #ef4444;
            color: white;
            border: none;
        }

        .btn-delete:hover {
            background-color: #dc2626;
        }

        form.inline {
            display: inline;
        }

        .form-box {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .form-box form {
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 15px;
            }

            h1 {
                font-size: 24px;
            }

            .btn-link {
                margin-top: 5px;
                width: 100%;
                text-align: center;
            }

            .form-box form {
                max-width: 100%;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Mirësevjen në User Dashboard</h1>
    <p>Përdorues: {{ auth()->user()->name }}</p>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Dil</button>
    </form>

    <h4>Regjistro Klient</h4>
    <div class="form-box">
        <form method="POST" action="/klientet">
            @csrf
            <input type="text" name="emri" placeholder="Emri" required>
            <input type="text" name="mbiemri" placeholder="Mbiemri" required>
            <input type="text" name="telefoni" placeholder="Telefoni">
            <input type="text" name="produkti" placeholder="Produkti">
            <input type="number" name="sasia" placeholder="Sasia" required>
            <input type="number" name="qmimi" placeholder="Qmimi (€)" required>
            <button type="submit">Regjistro Klientin</button>
        </form>
    </div>

    <h4>Klientët e sotëm</h4>
    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>Emri</th>
                <th>Mbiemri</th>
                <th>Produkti</th>
                <th>Sasia</th>
                <th>Qmimi</th>
                <th>Total (€)</th>
                <th>Veprime</th>
            </tr>
            </thead>
            <tbody>
            @php $totali = 0; @endphp
            @foreach($klientet as $k)
                @php $totalKlient = $k->sasia * $k->qmimi; $totali += $totalKlient; @endphp
                <tr>
                    <td>{{ $k->emri }}</td>
                    <td>{{ $k->mbiemri }}</td>
                    <td>{{ $k->produkti }}</td>
                    <td>{{ $k->sasia }}</td>
                    <td>{{ $k->qmimi }} €</td>
                    <td>{{ number_format($totalKlient, 2) }} €</td>
                    <td>
                        <a href="{{ route('klient.invoice', $k->id) }}" class="btn-link btn-invoice" target="_blank">Invoice</a>
                        <a href="{{ url('/klientet/' . $k->id . '/edit') }}" class="btn-link btn-edit">Edito</a>
                        <form action="{{ route('klientet.destroy', $k->id) }}" method="POST" class="inline" onsubmit="return confirm('A je i sigurt që do ta fshish këtë klient?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-link btn-delete">Fshije</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
