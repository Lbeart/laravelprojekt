<!DOCTYPE html>
<html>
<head>
    <title>Shto Punëtor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2>Shto Punëtor të Ri</h2>

    <form method="POST" action="{{ route('admin.punetoret.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Emri</label>
            <input type="text" class="form-control" name="name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Emaili</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Fjalëkalimi</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Shto</button>
    </form>
</body>
</html>