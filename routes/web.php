<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KlientiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Klient routes
Route::get('/brillants/create', [KlientiController::class, 'A']);
Route::post('/brillants', [KlientiController::class, 'B']);
Route::get('/klientet', [KlientiController::class, 'C']);
Route::get('/klientet/{id}/edit', [KlientiController::class, 'edit']);
Route::put('/klientet/{id}', [KlientiController::class, 'update']);
Route::delete('/klientet/{id}', [KlientiController::class, 'destroy']);
Route::get('/klientet/{id}/invoice', [KlientiController::class, 'invoice'])->name('klient.invoice');
Route::post('/klientet', [KlientiController::class, 'B'])->name('klientet.store');

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// User dashboard
Route::middleware(['auth', 'role:user'])->get('/user/dashboard', function () {
    $klientet = \App\Models\Klienti::where('user_id', auth()->id())
        ->whereDate('created_at', today())
        ->get();

    return view('user.dashboard', compact('klientet'));
});

// Admin dashboard + protected routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::delete('/admin/punetoret/{id}', [AdminController::class, 'destroy'])->name('admin.punetoret.destroy');
    Route::get('/admin/punetoret/krijo', [AdminController::class, 'createWorker'])->name('admin.punetoret.create');
    Route::post('/admin/punetoret', [AdminController::class, 'storeWorker'])->name('admin.punetoret.store');
    Route::get('/admin/punetoret/{id}/pagesa', [AdminController::class, 'showPaymentForm'])->name('admin.punetoret.pagesa.form');
    Route::post('/admin/punetoret/{id}/pagesa', [AdminController::class, 'storePayment'])->name('admin.punetoret.pagesa.store');
    Route::put('/admin/punetoret/{id}/update-salary', [AdminController::class, 'updateSalary'])->name('admin.punetoret.update.salary');
    Route::get('/admin/punetoret/{id}/invoice', [AdminController::class, 'invoice'])->name('admin.punetoret.invoice');
    Route::get('/admin/faturat/{muaji}', [AdminController::class, 'faturatPerMuaj'])->name('admin.fatura.muaji');
});

Route::middleware(['auth', 'role:user'])->get('/user/dashboard', function () {
    $klientet = \App\Models\Klienti::where('user_id', auth()->id())
        ->whereDate('created_at', today())
        ->get();

    return view('user.dashboard', compact('klientet'));
})->name('user.dashboard');
Route::delete('/klientet/{id}', [KlientiController::class, 'destroy'])->name('klientet.destroy');
Route::get('/admin/kerko-klient', [AdminController::class, 'kerkoKlient'])->name('admin.kerko.klient');


Route::get('/create-admin', function () {
    User::updateOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name'     => 'Admin',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]
    );

    return 'Admini u krijua me sukses ✅';
});