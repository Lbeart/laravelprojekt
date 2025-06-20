<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Dashboard i Adminit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3 bg-light">

<div class="container">

    <h2 class="mb-4 text-center text-primary">📋 Paneli i Punëtorëve</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ route('admin.punetoret.create') }}" class="btn btn-primary">➕ Shto Punëtor të Ri</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Emri</th>
                    <th>Emaili</th>
                    <th>Statusi</th>
                    <th>Rroga Mujore (€)</th>
                    <th>Pagesa e Marrë</th>
                    <th>Veprime</th>
                </tr>
            </thead>
            <tbody>
                @foreach($punetoret as $punetor)
                    <tr>
                        <td>{{ $punetor->name }}</td>
                        <td>{{ $punetor->email }}</td>
                        <td>{{ $punetor->active ? 'Aktiv' : 'Joaktiv' }}</td>
                        <td>
                            <form action="{{ route('admin.punetoret.update.salary', $punetor->id) }}" method="POST" class="d-flex flex-wrap align-items-center gap-1">
                                @csrf
                                @method('PUT')
                                <input type="number" name="monthly_salary" value="{{ $punetor->monthly_salary }}" class="form-control form-control-sm" style="width: 100px;">
                                <button type="submit" class="btn btn-sm btn-outline-primary">💾</button>
                            </form>
                        </td>
                        <td>{{ $punetor->payments()->whereMonth('payment_date', now()->month)->sum('amount') }} €</td>
                        <td class="d-flex flex-wrap gap-1">
                            <a href="{{ route('admin.punetoret.pagesa.form', $punetor->id) }}" class="btn btn-sm btn-success">💶 Pagesë</a>
                            <a href="{{ route('admin.punetoret.invoice', $punetor->id) }}" class="btn btn-sm btn-outline-secondary">🧾 Faturë</a>
                            <form action="{{ route('admin.punetoret.destroy', $punetor->id) }}" method="POST" onsubmit="return confirm('A je i sigurt që don me fshi këtë punëtor?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️ Fshi</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Statistikat për krejt --}}
    <div class="mt-4">
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <div class="col">
                <div class="bg-white p-3 shadow-sm rounded">
                    <p class="mb-1"><strong>📊 Totali i klientëve:</strong></p>
                    <h5>{{ $totaliKlienteve }}</h5>
                </div>
            </div>
            <div class="col">
                <div class="bg-white p-3 shadow-sm rounded">
                    <p class="mb-1"><strong>💰 Totali i pagesave nga klientët:</strong></p>
                    <h5>{{ number_format($totaliPagesave, 2) }} €</h5>
                </div>
            </div>
            <div class="col">
                <div class="bg-white p-3 shadow-sm rounded">
                    <p class="mb-1"><strong>📦 Totali i rrogave mujore:</strong></p>
                    <h5>{{ number_format($totaliRrogave, 2) }} €</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Shitjet Mujore --}}
    <div class="mt-5">
        <h4 class="mb-3 text-secondary">📆 Shitjet Mujore për {{ date('Y') }}</h4>
        <div class="table-responsive">
            <table class="table table-bordered bg-white">
                <thead class="table-secondary">
                    <tr>
                        <th>Muaji</th>
                        <th>Shitje Totale (€)</th>
                        <th>Fatura</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shitjetTotalePerMuaj as $muaji => $shuma)
                        <tr>
                            <td>{{ DateTime::createFromFormat('!m', $muaji)->format('F') }}</td>
                            <td>{{ number_format($shuma, 2) }} €</td>
                            <td>
                                <a href="{{ $faturaLinks[$muaji] }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                    🧾 Shfaq Faturat
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Butoni për dalje --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="btn btn-danger">🔒 Dil</button>
    </form>

</div>

</body>
</html>
