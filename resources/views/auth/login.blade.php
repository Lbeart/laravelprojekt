<!DOCTYPE html>
<html>
<head>
    <title>Kyçu në llogari</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        .logo {
            width: 160px;
            max-width: 100%;
            margin-bottom: 30px;
        }

        h2 {
            color: #1a237e;
            margin-bottom: 20px;
            text-align: center;
            font-size: 22px;
        }

        form {
            background-color: white;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #1a73e8;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0f5cd7;
        }

        .errors {
            background-color: #ffe6e6;
            color: #cc0000;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            form {
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            button {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

    <img src="{{ asset('images/llogo.png') }}" alt="Logo" class="logo">

    <h2>Kyçu në llogarinë tënde</h2>

    @if($errors->any())
        <div class="errors">
            <ul style="margin:0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Fjalëkalimi:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Kyçu</button>
    </form>
</body>
</html>