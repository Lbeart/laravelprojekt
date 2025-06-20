<!DOCTYPE html> 
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Fatura për {{ $klient->emri }} {{ $klient->mbiemri }}</title>
    <style>
        body { font-family: Arial; padding: 30px; }
        h1 { text-align: center; }
        .invoice-box { max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px; }
        .row { margin-bottom: 10px; }
        .label { font-weight: bold; }

        /* Stili i butonave */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 10px 10px 30px 0;
            font-size: 16px;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            color: white;
            background-color: #007bff;
            border: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function printInvoice() {
            window.print();
        }
    </script>
</head>
<body>

    <div class="invoice-box">
        <h1>Fatura</h1>
        <div class="row"><span class="label">Emri:</span> {{ $klient->emri }}</div>
        <div class="row"><span class="label">Mbiemri:</span> {{ $klient->mbiemri }}</div>
        <div class="row"><span class="label">Produkti:</span> {{ $klient->produkti }}</div>
        <div class="row"><span class="label">Sasia:</span> {{ $klient->sasia }}</div>
        <div class="row"><span class="label">Çmimi për njësi:</span> {{ number_format($klient->qmimi, 2) }} €</div>
        <hr>
        <div class="row" style="font-weight: bold;">
            Çmimi gjithsej: {{ number_format($gjithsej, 2) }} €
        </div>
    </div>

    <!-- Butoni për printim -->
    <button class="btn" onclick="printInvoice()">Printo</button>

    <!-- Butoni për kthim -->
    <a href="/klientet" class="btn" style="background-color: #28a745;">Kthehu te klientët</a>

</body>
</html>