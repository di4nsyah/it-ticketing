<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * MVC middleware: penyaring yg jalan sebelum request sampai ke controller
 * kalo nolak, request berhenti di sini, controller & database nggak disentuh
 *
 * CATATAN: ini udah terdaftar sbg alias 'teknisi' di bootstrap/app.php,
 * tapi BELUM dipake di route manapun. jadi penjaga role yg beneran jalan
 * itu TicketPolicy, bukan middleware ini
 */
class EnsureUserIsTeknisi
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 2 syarat: udah login, sama role-nya teknisi
        if (! $request->user() || ! $request->user()->isTeknisi()) {
            abort(403, 'Halaman ini hanya dapat diakses oleh teknisi.');
        }
        return $next($request);
    }
}
