<?php 

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use App\Models\Klienti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use Milon\Barcode\DNS1D;

class AdminController extends Controller
{
  public function index() 
{
    // Merr vetëm punëtorët aktiv
    $punetoret = User::where('role', 'user')->where('active', true)->get();

    foreach ($punetoret as $punetor) {
        // Shitjet e sotme
        $punetor->shitjetSot = Klienti::where('user_id', $punetor->id)
            ->whereDate('created_at', today())
            ->sum(DB::raw('sasia * qmimi'));

        // Klientët e sotëm
        $punetor->klientetSot = Klienti::where('user_id', $punetor->id)
            ->whereDate('created_at', today())
            ->count();

        // Gjithsej
        $punetor->klientetGjithsej = Klienti::where('user_id', $punetor->id)->count();
        $punetor->shitjeGjithsej = Klienti::where('user_id', $punetor->id)
            ->sum(DB::raw('sasia * qmimi'));

        // Shitjet mujore për 12 muajt e fundit
        $punetor->shitjetMujore = Klienti::select(
                DB::raw('MONTH(created_at) as muaji'),
                DB::raw('SUM(sasia * qmimi) as shuma')
            )
            ->where('user_id', $punetor->id)
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('shuma', 'muaji');
    }

    // Merr vetëm ID-të e punëtorëve aktiv
    $punetoretAktivIDs = User::where('role', 'user')->where('active', true)->pluck('id');

    // Rillogarite statistikat pas fshirjes për vetëm punëtorët aktiv
    $totaliKlienteve = Klienti::whereIn('user_id', $punetoretAktivIDs)->count();
    $totaliPagesave = Klienti::whereIn('user_id', $punetoretAktivIDs)->sum(DB::raw('sasia * qmimi'));
    $totaliRrogave = User::where('role', 'user')->where('active', true)->sum('monthly_salary');

    // Shitjet mujore për të gjithë punëtorët aktiv (për tabelën e përbashkët)
    $shitjetTotalePerMuaj = [];
    for ($muaji = 1; $muaji <= 12; $muaji++) {
        $shitjetTotalePerMuaj[$muaji] = Klienti::whereIn('user_id', $punetoretAktivIDs)
            ->whereMonth('created_at', $muaji)
            ->whereYear('created_at', now()->year)
            ->sum(DB::raw('sasia * qmimi'));
    }

    // Link për fatura për secilin muaj
    $faturaLinks = [];
    for ($muaji = 1; $muaji <= 12; $muaji++) {
        $faturaLinks[$muaji] = route('admin.fatura.muaji', ['muaji' => $muaji]);
    }

    return view('admin.dashboard', compact(
        'punetoret',
        'totaliKlienteve',
        'totaliPagesave',
        'totaliRrogave',
        'shitjetTotalePerMuaj',
        'faturaLinks'
    ));
}



    public function showPaymentForm($id)
    {
        $punetor = User::findOrFail($id);
        return view('admin.add_payment', compact('punetor'));
    }

    public function storePayment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:daily,monthly',
            'payment_date' => 'required|date',
        ]);

        Payment::create([
            'user_id' => $id,
            'amount' => $request->amount,
            'type' => $request->type,
            'payment_date' => $request->payment_date,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Pagesa u shtua me sukses.');
    }

    public function createWorker()
    {
        return view('admin.create_worker');
    }

    public function storeWorker(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'active' => true,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Punëtori u shtua me sukses.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Nuk mund të fshish adminin.');
        }

        // Fshij klientët e lidhur me këtë punëtor
        Klienti::where('user_id', $user->id)->delete();

        $user->delete();

        // Pas fshirjes redirekto për të rifreskuar statistikat
        return redirect()->route('admin.dashboard')->with('success', 'Punëtori dhe klientët e tij u fshinë me sukses.');
    }

    public function updateSalary(Request $request, $id)
    {
        $request->validate([
            'monthly_salary' => 'required|numeric|min:0'
        ]);

        $user = User::findOrFail($id);
        $user->monthly_salary = $request->monthly_salary;
        $user->save();

        return back()->with('success', 'Rroga mujore u përditësua me sukses.');
    }

    public function invoice($id)
    {
        $punetor = User::findOrFail($id);

        if ($punetor->role !== 'user') {
            abort(403, 'Nuk lejohet.');
        }

        $pagesat = $punetor->payments()->whereMonth('payment_date', now()->month)->get();
        $shumaTotale = $pagesat->sum('amount');

        return view('admin.punetoret.invoice', compact('punetor', 'pagesat', 'shumaTotale'));
    }
    public function faturatPerMuaj($muaji)
{
    $punetoretAktivIDs = User::where('role', 'user')->where('active', true)->pluck('id');

    $klientet = Klienti::whereIn('user_id', $punetoretAktivIDs)
        ->whereMonth('created_at', $muaji)
        ->whereYear('created_at', now()->year)
        ->get();

    return view('admin.faturat-muaj', compact('klientet', 'muaji'));
}
public function kerkoKlient(Request $request)
{
    $emri = $request->input('emri');

    $klient = Klienti::where('emri', 'like', "%$emri%")
        ->orWhere('mbiemri', 'like', "%$emri%")
        ->first();

    if ($klient) {
        return redirect()->route('klient.invoice', $klient->id);
    } else {
        return back()->with('error', 'Klienti nuk u gjet.');
    }
}
};