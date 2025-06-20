<!DOCTYPE html>
<html>
<head>
    <title>Shto Pagesë</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2>Shto Pagesë për: {{ $punetor->name }}</h2>

    <form method="POST" action="{{ route('admin.punetoret.pagesa.store', $punetor->id) }}">
        @csrf

        <div class="mb-3">
            <label for="amount" class="form-label">Shuma (€)</label>
            <input type="number" name="amount" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Lloji</label>
            <select name="type" class="form-select" required>
                <option value="daily">Ditore</option>
                <option value="monthly">Mujore</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="payment_date" class="form-label">Data</label>
            <input type="date" name="payment_date" class="form-control" value="{{ now()->toDateString() }}" required>
        </div>

        <button type="submit" class="btn btn-success">Ruaj Pagesën</button>
    </form>
</body>
</html>