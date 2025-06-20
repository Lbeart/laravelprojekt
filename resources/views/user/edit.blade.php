<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edito Klientin</title>
    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-wrapper {
            width: 90%;
            max-width: 320px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 18px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="number"] {
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
        }

        button {
            padding: 10px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #0b5ed7;
        }

        @media (max-height: 500px) {
            body {
                align-items: flex-start;
                padding-top: 20px;
            }
        }
    </style>
</head>
<body>

<div class="form-wrapper">
    <h2>Edito Klientin</h2>
    <form method="POST" action="{{ url('/klientet/' . $klient->id) }}">
        @csrf
        @method('PUT')

        <input type="text" name="emri" value="{{ $klient->emri }}" placeholder="Emri" required>
        <input type="text" name="mbiemri" value="{{ $klient->mbiemri }}" placeholder="Mbiemri" required>
        <input type="text" name="telefoni" value="{{ $klient->telefoni }}" placeholder="Telefoni">
        <input type="text" name="produkti" value="{{ $klient->produkti }}" placeholder="Produkti">
        <input type="number" name="sasia" value="{{ $klient->sasia }}" placeholder="Sasia" required>
        <input type="number" name="qmimi" value="{{ $klient->qmimi }}" placeholder="Qmimi (€)" required>
        
        <button type="submit">Përditëso</button>
    </form>
</div>

</body>
</html>
