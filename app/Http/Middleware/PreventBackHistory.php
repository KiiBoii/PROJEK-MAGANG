<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Dapatkan response dari request selanjutnya
        $response = $next($request);

        // 2. Tambahkan header untuk melarang browser caching
        // Ini memaksa browser untuk selalu meminta halaman baru dari server
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0'); // Gunakan '0' atau tanggal di masa lalu

        // 3. Kembalikan response yang sudah dimodifikasi
        return $response;
    }
}