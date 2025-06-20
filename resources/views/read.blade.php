<!DOCTYPE html> 
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Lista e Klientëve</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form.search-form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 8px;
            width: 300px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            margin-right: 5px;
        }

        .btn-add {
            background-color: #007bff;
            color: white;
            margin-bottom: 20px;
        }

        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-invoice {
            background-color: #ff9800;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #eaeaea;
        }

        tfoot td {
            font-weight: bold;
            background-color: #ddd;
            text-align: right;
        }

        .success-message {
            color: green;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Lista e Klientëve</h2>

@if(session('success'))
    <div class="success-message">
        {{ session('success') }}
    </div>
@endif

<form action="{{ url('/klientet') }}" method="GET" class="search-form">
    <input type="text" name="search" placeholder="Kërko me emër ose mbiemër" value="{{ request('search') }}">
    <button type="submit" class="btn btn-add">Kërko</button>
    <a href="/brillants/create" class="btn btn-add">+ Shto Klient të Ri</a>
</form>

<table>
    <thead>
        <tr>
            <th>Emri</th>
            <th>Mbiemri</th>
            <th>Telefoni</th>
            <th>Produkti</th>
            <th>Sasia</th>
            <th>Qmimi (€)</th>
            <th>Çmimi Gjithsej (€)</th>
            <th>Data e Shitjes</th>
            <th>Veprime</th>
           
        </tr>
    </thead>
    <tbody>
        @php $totalGjithsej = 0; @endphp
        @foreach($klientet as $klient)
            @php
                $gjithsej = $klient->qmimi * $klient->sasia;
                $totalGjithsej += $gjithsej;
            @endphp
            <tr>
                <td>{{ $klient->emri }}</td>
                <td>{{ $klient->mbiemri }}</td>
                <td>{{ $klient->telefoni }}</td>
                <td>{{ $klient->produkti }}</td>
                <td>{{ $klient->sasia }}</td>
                <td>{{ number_format($klient->qmimi, 2) }}</td>
                <td>{{ number_format($gjithsej, 2) }}</td>
             <td>{{ \Carbon\Carbon::parse($klient->data_e_shitjes)->format('d-m-Y') }}</td>
                <td>
                    <a href="/klientet/{{ $klient->id }}/edit" class="btn btn-edit">Edito</a>
                    <a href="/klientet/{{ $klient->id }}/invoice" class="btn btn-invoice">Invoice</a>
                    <form action="/klientet/{{ $klient->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" onclick="return confirm('A je i sigurt që don me fshi këtë klient?')">Fshij</button>
                    </form>
                </td>
                
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="text-align: right;">Totali i Pagesave (€):</td>
            <td colspan="2">{{ number_format($totalGjithsej, 2) }}</td>
        </tr>
    </tfoot>
</table>

</body>
</html>
