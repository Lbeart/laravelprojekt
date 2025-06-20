<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Forma për të dhëna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 30px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #444;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>

    <div class="form-container">
        <h2>Shto të dhëna</h2>
        <form action="/brillants" method="POST">
            <!-- Laravel CSRF token -->
             @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label for="emri">Emri</label>
                <input type="text" id="emri" name="emri" required>
            </div>

            <div class="form-group">
                <label for="mbiemri">Mbiemri</label>
                <input type="text" id="mbiemri" name="mbiemri" required>
            </div>

            <div class="form-group">
                <label for="telefoni">Telefoni</label>
            <input type="text" name="telefoni" required value="{{ old('telefoni') }}">
            </div>

            <div class="form-group">
                <label for="produkti">Produkti</label>
                <input type="text" id="produkti" name="produkti" required>
            </div>

            <div class="form-group">
                <label for="sasia">Sasia</label>
                <input type="number" id="sasia" name="sasia" required>
            </div>

            <div class="form-group">
                <label for="qmimi">Qmimi (€)</label>
               <input type="number" step="0.01" name="qmimi" value="{{ old('qmimi') }}" required>
            </div>

            <button type="submit">Ruaj të dhënat</button>
        </form>
    </div>

</body>
</html>