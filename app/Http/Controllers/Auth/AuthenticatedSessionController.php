<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/*
 * MVC authentication: ngurus login & logout
 *
 * alur: POST /login -> cek password di tabel users -> session di-regenerate
 * -> user dianggap login -> auth()->user() ngasih data user itu ke controller & view
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // authenticate() di LoginRequest yg cek kredensialnya, kalo gagal lempar error
        $request->authenticate();

        // ganti id session tiap login, biar session lama nggak bisa dipake orang lain
        $request->session()->regenerate();

        // lempar ke halaman yg tadi mau dibuka, kalo nggak ada fallback ke dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // logout cuma buat session, data usernya tetep ada di database
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
