<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\klienti;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

   public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }

    return back()->withErrors([
        'email' => 'Email ose fjalëkalimi është gabim.',
    ]);
}
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function userDashboard()
{
    $klientet = Klienti::where('user_id', auth()->id())
        ->whereDate('created_at', today())
        ->get();

    return view('user.dashboard', compact('klientet'));
}

}